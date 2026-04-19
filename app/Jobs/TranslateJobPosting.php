<?php

namespace App\Jobs;

use App\Models\Job;
use App\Services\TranslationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TranslateJobPosting implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(public Job $jobPosting)
    {
    }

    public function handle(TranslationService $translator): void
    {
        try {
            $fields = ['title', 'description', 'requirements'];

            foreach ($fields as $field) {
                $original = $this->jobPosting->{$field};

                if (empty($original)) {
                    continue;
                }

                $context = $field === 'title' ? 'title' : 'text';
                $translated = $translator->translate($original, $context);

                $enField = "{$field}_en";
                $this->jobPosting->{$enField} = $translated;
            }

            $this->jobPosting->translation_status = 'done';
            $this->jobPosting->save();

            Log::info('Job posting translated successfully', ['job_id' => $this->jobPosting->id]);
        } catch (\Exception $e) {
            Log::error('Job posting translation failed', [
                'job_id' => $this->jobPosting->id,
                'error' => $e->getMessage(),
            ]);

            $this->jobPosting->translation_status = 'failed';
            $this->jobPosting->save();
        }
    }
}
