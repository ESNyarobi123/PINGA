<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class LegacyWordPressUserImporter
{
    /**
     * @param  callable(string, string): void|null  $onSkip
     * @param  callable(string): void|null  $onProgress
     * @return array{created: int, updated: int, skipped: int, reset_links_sent: int}
     */
    public function import(
        Connection $legacy,
        bool $dryRun = false,
        ?int $limit = null,
        bool $sendResetLinks = false,
        int $chunkSize = 100,
        ?callable $onSkip = null,
        ?callable $onProgress = null,
    ): array {
        $stats = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'reset_links_sent' => 0,
        ];

        $guard = (string) config('auth.defaults.guard', 'web');
        foreach (['mteja', 'winga'] as $roleName) {
            Role::query()->firstOrCreate(
                ['name' => $roleName, 'guard_name' => $guard],
            );
        }

        $processed = 0;
        $legacy->table('wp_users')->orderBy('ID')->chunkById($chunkSize, function (Collection $rows) use (
            &$stats,
            &$processed,
            $legacy,
            $dryRun,
            $limit,
            $sendResetLinks,
            $onSkip,
            $onProgress,
        ) {
            $ids = $rows->pluck('ID')->all();
            $metaByUser = $legacy->table('wp_usermeta')
                ->whereIn('user_id', $ids)
                ->get()
                ->groupBy('user_id');

            foreach ($rows as $wp) {
                if ($limit !== null && $processed >= $limit) {
                    return false;
                }
                $processed++;

                $meta = $metaByUser->get($wp->ID, collect())->keyBy('meta_key');
                $email = self::normalizeEmail($wp->user_email ?? '');
                if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $stats['skipped']++;
                    $onSkip?->__invoke('invalid_email', (string) ($wp->user_email ?? ''));

                    continue;
                }

                if ((int) ($wp->user_status ?? 0) !== 0) {
                    $stats['skipped']++;
                    $onSkip?->__invoke('inactive_user_status', $email);

                    continue;
                }

                $caps = $meta->get('wp_capabilities')?->meta_value;
                if (self::isWordPressAdministrator($caps)) {
                    $stats['skipped']++;
                    $onSkip?->__invoke('administrator', $email);

                    continue;
                }

                $role = self::resolveAppRole($meta->get('hp_vendor')?->meta_value);
                $name = self::resolveDisplayName($wp, $meta);
                $phone = self::normalizePhone($meta->get('billing_phone')?->meta_value);
                $registeredAt = self::parseWpDate($wp->user_registered ?? null);

                $existingByLegacy = User::query()->where('legacy_wp_user_id', (int) $wp->ID)->first();
                $existingByEmail = User::query()->whereRaw('lower(email) = ?', [Str::lower($email)])->first();

                if ($existingByLegacy === null && $existingByEmail !== null && $existingByEmail->legacy_wp_user_id !== null && $existingByEmail->legacy_wp_user_id !== (int) $wp->ID) {
                    $stats['skipped']++;
                    $onSkip?->__invoke('email_owned_by_other_legacy_user', $email);

                    continue;
                }

                if ($existingByLegacy === null && $existingByEmail !== null && $existingByEmail->legacy_wp_user_id === null) {
                    $stats['skipped']++;
                    $onSkip?->__invoke('email_exists_without_legacy_id', $email);

                    continue;
                }

                $payload = [
                    'name' => $name,
                    'email' => Str::lower($email),
                    'role' => $role,
                    'legacy_wp_user_id' => (int) $wp->ID,
                    'email_verified_at' => $registeredAt ?? now(),
                    'onboarding_completed' => true,
                ];

                if ($phone !== null) {
                    $excludeId = ($existingByLegacy ?? $existingByEmail)?->id;
                    $conflict = $excludeId
                        ? User::query()->where('phone', $phone)->where('id', '!=', $excludeId)->exists()
                        : User::query()->where('phone', $phone)->exists();
                    if (! $conflict) {
                        $payload['phone'] = $phone;
                    }
                }

                if ($dryRun) {
                    if ($existingByLegacy ?? $existingByEmail) {
                        $stats['updated']++;
                    } else {
                        $stats['created']++;
                    }
                    $onProgress?->__invoke($email);

                    continue;
                }

                if ($existingByLegacy ?? $existingByEmail) {
                    /** @var User $user */
                    $user = $existingByLegacy ?? $existingByEmail;
                    unset($payload['email']);
                    $user->update($payload);
                    $user->syncRoles([$role]);
                    $stats['updated']++;
                } else {
                    $payload['password'] = Str::password(40);
                    $user = User::query()->create($payload);
                    $user->syncRoles([$role]);
                    $stats['created']++;
                    if ($sendResetLinks) {
                        $status = Password::broker()->sendResetLink(['email' => $user->email]);
                        if ($status === Password::RESET_LINK_SENT) {
                            $stats['reset_links_sent']++;
                        }
                    }
                }

                $onProgress?->__invoke($email);
            }

            return true;
        }, 'ID');

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $stats;
    }

    public static function isWordPressAdministrator(?string $wpCapabilitiesSerialized): bool
    {
        if ($wpCapabilitiesSerialized === null || $wpCapabilitiesSerialized === '') {
            return false;
        }

        $decoded = @unserialize($wpCapabilitiesSerialized, ['allowed_classes' => false]);
        if (! is_array($decoded)) {
            return false;
        }

        return ! empty($decoded['administrator']);
    }

    public static function resolveAppRole(?string $hpVendorMeta): string
    {
        $v = trim((string) $hpVendorMeta);

        if ($v !== '' && $v !== '0') {
            return 'winga';
        }

        return 'mteja';
    }

    /**
     * @param  object{display_name?: string|null, user_login?: string|null}  $wp
     * @param  Collection<string, object{meta_key: string, meta_value: string|null}>  $meta
     */
    public static function resolveDisplayName(object $wp, Collection $meta): string
    {
        $display = trim((string) ($wp->display_name ?? ''));
        if ($display !== '') {
            return Str::limit($display, 255, '');
        }

        $first = trim((string) ($meta->get('first_name')?->meta_value ?? ''));
        $last = trim((string) ($meta->get('last_name')?->meta_value ?? ''));
        $full = trim($first.' '.$last);
        if ($full !== '') {
            return Str::limit($full, 255, '');
        }

        $login = trim((string) ($wp->user_login ?? ''));
        if ($login !== '') {
            return Str::limit($login, 255, '');
        }

        return 'Imported user';
    }

    public static function normalizeEmail(string $email): string
    {
        return trim(Str::lower($email));
    }

    public static function normalizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === null || $digits === '') {
            return null;
        }

        return Str::limit($digits, 32, '');
    }

    public static function parseWpDate(?string $value): ?Carbon
    {
        if ($value === null || $value === '' || $value === '0000-00-00 00:00:00') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
