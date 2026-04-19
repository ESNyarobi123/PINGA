<?php

namespace App\Console\Commands;

use App\Services\LegacyWordPressUserImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportLegacyWordPressUsers extends Command
{
    protected $signature = 'import:legacy-wp-users
                            {--connection=legacy_wp : Database connection for the legacy WordPress MySQL database}
                            {--dry-run : Show counts without writing to the application database}
                            {--limit= : Maximum number of wp_users rows to consider}
                            {--chunk=100 : Chunk size for reading wp_users}
                            {--send-reset-links : Queue password reset emails for newly created users only}';

    protected $description = 'Import WordPress users into Winga with new random passwords (option B). Skips WP administrators.';

    public function handle(LegacyWordPressUserImporter $importer): int
    {
        $name = (string) $this->option('connection');
        if (! Config::has("database.connections.{$name}")) {
            $this->error("Unknown database connection [{$name}].");

            return self::FAILURE;
        }

        try {
            DB::connection($name)->getPdo();
        } catch (Throwable $e) {
            $this->error('Could not connect to the legacy database: '.$e->getMessage());

            return self::FAILURE;
        }

        $legacy = DB::connection($name);
        if (! $legacy->getSchemaBuilder()->hasTable('wp_users') || ! $legacy->getSchemaBuilder()->hasTable('wp_usermeta')) {
            $this->error('Legacy database must contain wp_users and wp_usermeta tables.');

            return self::FAILURE;
        }

        $limit = $this->option('limit');
        $limit = $limit !== null && $limit !== '' ? (int) $limit : null;
        $chunk = max(1, (int) $this->option('chunk'));

        $verbose = $this->output->isVerbose();
        $stats = $importer->import(
            $legacy,
            (bool) $this->option('dry-run'),
            $limit,
            (bool) $this->option('send-reset-links'),
            $chunk,
            $verbose ? function (string $reason, string $detail): void {
                $this->warn("skip [{$reason}]: {$detail}");
            } : null,
            $verbose ? function (string $email): void {
                $this->line("ok: {$email}");
            } : null,
        );

        $this->table(
            ['Metric', 'Count'],
            collect($stats)->map(fn (int $v, string $k) => [$k, (string) $v])->values()->all(),
        );

        if ($this->option('dry-run')) {
            $this->warn('Dry run only — no users were written.');
        } elseif (! $this->option('send-reset-links') && $stats['created'] > 0) {
            $this->comment('New users cannot log in with their old WordPress password. Run with --send-reset-links or ask users to use “Forgot password”.');
        }

        return self::SUCCESS;
    }
}
