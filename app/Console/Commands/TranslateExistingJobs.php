<?php

namespace App\Console\Commands;

use App\Jobs\TranslateJobPosting;
use App\Models\Job;
use Illuminate\Console\Command;

class TranslateExistingJobs extends Command
{
    protected $signature = 'jobs:translate-existing {--failed : Also retry failed translations}';

    protected $description = 'Dispatch translation jobs for all pending (and optionally failed) job postings';

    public function handle(): int
    {
        $query = Job::where('translation_status', 'pending');

        if ($this->option('failed')) {
            $query->orWhere('translation_status', 'failed');
        }

        $count = $query->count();

        if ($count === 0) {
            $this->info('No job postings need translation.');

            return self::SUCCESS;
        }

        $this->info("Found {$count} job postings to translate.");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $dispatched = 0;

        $query->chunkById(50, function ($jobs) use (&$dispatched, $bar) {
            foreach ($jobs as $job) {
                TranslateJobPosting::dispatch($job);
                $dispatched++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Dispatched {$dispatched} translation jobs to the queue.");

        return self::SUCCESS;
    }
}
