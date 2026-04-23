<?php

namespace App\Services;

use App\Models\Setting;

final class SuspensionAppealContact
{
    public static function appealEmail(): string
    {
        $specific = Setting::get('suspension_appeal_email');

        if (is_string($specific) && $specific !== '') {
            return $specific;
        }

        $fallback = Setting::get('support_email');

        if (is_string($fallback) && $fallback !== '') {
            return $fallback;
        }

        return (string) config('mail.from.address', 'support@example.com');
    }

    public static function appealWhatsappDigits(): string
    {
        $specific = Setting::get('suspension_appeal_whatsapp');
        $raw = (is_string($specific) && $specific !== '')
            ? $specific
            : (string) (Setting::get('support_phone') ?? '');

        return self::normalizeTzWhatsapp($raw);
    }

    /**
     * @param  array{email: string, role_label: string, reason: ?string}  $appeal
     */
    public static function whatsappPrefillMessage(array $appeal): string
    {
        $reason = $appeal['reason'] ?? null;
        $reasonLine = $reason
            ? "Sababu iliyoonyeshwa na mfumo: {$reason}\n"
            : '';

        return "Habari,\n\n"
            ."Ninaomba maelezo kuhusu akaunti yangu iliyosimamishwa/fungwa na msimamizi.\n\n"
            ."Barua pepe ya akaunti: {$appeal['email']}\n"
            ."Jukumu (role): {$appeal['role_label']}\n"
            .$reasonLine
            ."\nAsante.";
    }

    public static function whatsappUrl(?array $appeal = null): ?string
    {
        $digits = self::appealWhatsappDigits();
        if ($digits === '') {
            return null;
        }

        $text = $appeal !== null
            ? self::whatsappPrefillMessage($appeal)
            : 'Habari, ninaomba msaada kuhusu akaunti yangu iliyosimamishwa. Asante.';

        return 'https://wa.me/'.$digits.'?text='.rawurlencode($text);
    }

    private static function normalizeTzWhatsapp(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $digits = '255'.substr($digits, 1);
        }

        if (! str_starts_with($digits, '255') && strlen($digits) === 9) {
            $digits = '255'.$digits;
        }

        return $digits;
    }
}
