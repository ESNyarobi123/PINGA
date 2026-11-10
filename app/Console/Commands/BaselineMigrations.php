<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaselineMigrations extends Command
{
    protected $signature = 'db:baseline
        {--dry-run : Show what would be marked as already migrated}
        {--before= : Only baseline migrations timestamped before this date (e.g. 2026-11-01)}
        {--only= : Comma separated migrations to baseline (instead of the --before rule)}
        {--except= : Comma separated migrations to leave pending}
        {--force : Skip the confirmation prompt}';

    protected $description = 'Record existing migrations as already run when the database has tables but the migrations table is empty';

    public function handle(): int
    {
        $this->ensureMigrationsTable();

        $existing = array_flip(DB::table('migrations')->pluck('migration')->all());

        $files = collect(glob(database_path('migrations/*.php')))
            ->map(fn (string $path): string => basename($path, '.php'))
            ->sort()
            ->values();

        $missing = $files->reject(fn (string $name): bool => isset($existing[$name]))->values();

        if ($missing->isEmpty()) {
            $this->info('Kila migration imekwisha rekodiwa. Hakuna cha kufanya.');

            return self::SUCCESS;
        }

        $toBaseline = $this->selectForBaseline($missing);
        $remaining = $missing->diff($toBaseline)->values();

        $this->newLine();
        $this->line('Migration zilizopo kwenye mfumo: '.$files->count()
            .' | zilizorekodiwa: '.count($existing)
            .' | hazijarekodiwa: '.$missing->count());
        $this->newLine();

        if ($toBaseline->isNotEmpty()) {
            $this->table(
                ['Zitawekwa kama "zimesha run" ('.count($toBaseline).')'],
                $toBaseline->take(15)->map(fn (string $name): array => [$name])->all()
            );

            if ($toBaseline->count() > 15) {
                $this->line('... na '.($toBaseline->count() - 15).' nyingine.');
            }
        }

        if ($remaining->isNotEmpty()) {
            $this->table(
                ['Zitabaki pending — zitahitaji `php artisan migrate`'],
                $remaining->map(fn (string $name): array => [$name])->all()
            );
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry run: hakuna kilichobadilishwa kwenye database.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Rekodi migration hizi kama zimesha run?', true)) {
            $this->warn('Imeahirishwa.');

            return self::FAILURE;
        }

        $batch = ((int) DB::table('migrations')->max('batch')) + 1;

        foreach ($toBaseline->chunk(100) as $chunk) {
            DB::table('migrations')->insert(
                $chunk->map(fn (string $name): array => ['migration' => $name, 'batch' => $batch])->all()
            );
        }

        $this->newLine();
        $this->info(count($toBaseline)." migration zimerekodiwa kwenye batch {$batch}.");

        if ($remaining->isNotEmpty()) {
            $this->newLine();
            $this->warn('Hatua inayofuata: `php artisan migrate --force`');
            $this->line('Kama moja ikifeli kwa "Duplicate column/table already exists", rekodi kama ilishafanyika:');
            $this->line('  php artisan db:baseline --force --only=<jina_la_migration>');

            return self::SUCCESS;
        }

        $this->info('Hakuna migration iliyobaki. `php artisan migrate` haitafanya kitu.');

        return self::SUCCESS;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, string>  $missing
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function selectForBaseline($missing)
    {
        $only = $this->listOption('only');
        $except = $this->listOption('except');

        if ($only !== []) {
            return $missing->filter(fn (string $name): bool => in_array($name, $only, true))->values();
        }

        $cutoff = $this->parseCutoff($this->option('before'));

        return $missing->filter(function (string $name) use ($cutoff, $except): bool {
            if (in_array($name, $except, true)) {
                return false;
            }

            if ($cutoff === null) {
                return true;
            }

            $timestamp = $this->timestampOf($name);

            return $timestamp !== null && $timestamp < $cutoff;
        })->values();
    }

    /**
     * @return array<int, string>
     */
    private function listOption(string $name): array
    {
        return collect(explode(',', (string) $this->option($name)))
            ->map(fn (string $value): string => trim($value))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Normalise a date/option value to a 14 character timestamp (YYYYMMDDHHMMSS).
     */
    private function parseCutoff(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', (string) $value);

        if ($digits === null || $digits === '') {
            return null;
        }

        return str_pad($digits, 14, '0');
    }

    private function timestampOf(string $migration): ?string
    {
        if (! preg_match('/^(\d{4}_\d{2}_\d{2}_\d{6})_/', $migration, $matches)) {
            return null;
        }

        return str_replace('_', '', $matches[1]);
    }

    private function ensureMigrationsTable(): void
    {
        if (Schema::hasTable('migrations')) {
            return;
        }

        Schema::create('migrations', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('migration');
            $table->integer('batch');
        });

        $this->warn('Table ya `migrations` haikuwa ipo — nimeiunda sasa.');
    }
}
