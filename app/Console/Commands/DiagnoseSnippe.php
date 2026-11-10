<?php

namespace App\Console\Commands;

use App\Models\WithdrawalRequest;
use App\Services\SnippePayoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DiagnoseSnippe extends Command
{
    protected $signature = 'snippe:diagnose {--no-api : Skip the live API check}';

    protected $description = 'Check Snippe configuration, merchant balance and stuck payouts before approving withdrawals';

    public function handle(SnippePayoutService $snippe): int
    {
        $appUrl = (string) config('app.url');
        $apiUrl = (string) config('services.snippe.url');
        $key = (string) config('services.snippe.key');
        $secret = (string) config('services.snippe.webhook_secret');

        $rows = [
            ['APP_URL', $appUrl],
            ['SNIPPE_URL', $apiUrl],
            ['Payment webhook', $apiUrl === '' ? '-' : $this->webhookBase($appUrl).'/api/webhooks/snippe'],
            ['Payout webhook', $snippe->webhookUrl()],
            ['API key', $key === '' ? 'MISSING' : 'set ('.strlen($key).' chars)'],
            ['Webhook secret', $secret === '' ? 'MISSING (signatures are not verified)' : 'set'],
        ];

        $this->newLine();
        $this->table(['Setting', 'Value'], $rows);

        $problems = [];

        if ($key === '') {
            $problems[] = 'SNIPPE_API_KEY haipo — hakuna payout wala malipo yatakayotumwa.';
        }

        if ($secret === '') {
            $problems[] = 'SNIPPE_WEBHOOK_SECRET haipo — webhook za malipo hazithibitishwi kwa signature.';
        }

        if ($this->hostMismatch($appUrl, $snippe->webhookUrl())) {
            $problems[] = 'Webhook host hailingani na APP_URL. Snippe itatuma uthibitisho kwenye '.$this->host($snippe->webhookUrl())
                .' badala ya '.$this->host($appUrl).'. Weka SNIPPE_WEBHOOK_BASE_URL='.$appUrl.' kisha `php artisan config:clear`.';
        }

        $stuck = WithdrawalRequest::query()
            ->where('payout_status', 'processing')
            ->where('status', '!=', 'paid')
            ->count();

        if ($stuck > 0) {
            $problems[] = $stuck.' withdrawal iko "processing" bila uthibitisho. Angalia dashboard ya Snippe kabla ya kutuma tena.';
        }

        $balanceFailures = WithdrawalRequest::query()
            ->whereNotNull('payout_error')
            ->where('payout_error', 'like', '%Salio la akaunti ya Snippe%')
            ->where('status', 'pending')
            ->count();

        if ($balanceFailures > 0) {
            $problems[] = $balanceFailures.' ombi limeshindikana kwa sababu salio la Snippe halitoshi (PAY_004). Weka salio kwenye dashboard ya Snippe kisha bonyeza "Jaribu Tena".';
        }

        if (! $this->option('no-api') && $key !== '') {
            $this->newLine();
            $this->line('Inajaribu API ya Snippe (fee preview, haitumi pesa)...');

            try {
                $startedAt = microtime(true);

                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$key}",
                    'Accept' => 'application/json',
                ])->connectTimeout(10)->timeout(20)->get(rtrim($apiUrl, '/').'/v1/payouts/fee', ['amount' => 1000]);

                $elapsed = (int) round((microtime(true) - $startedAt) * 1000);

                if ($response->successful()) {
                    $this->info("API iko sawa ({$elapsed}ms): ".json_encode($response->json('data') ?? $response->json()));

                    if ($elapsed > 5000) {
                        $problems[] = "API ya Snippe inachelewa ({$elapsed}ms). Mtandao wa server kwenda api.snippe.sh ni wa polepole — malipo yanaweza kufeli kwa timeout (cURL error 28).";
                    }
                } else {
                    $problems[] = "API ya Snippe imejibu {$response->status()} baada ya {$elapsed}ms: ".($response->json('message') ?? $response->body());
                }
            } catch (\Exception $e) {
                $problems[] = 'Imeshindwa kufikia API ya Snippe: '.$e->getMessage();
            }
        }

        $this->newLine();

        if ($problems !== []) {
            foreach ($problems as $problem) {
                $this->error('• '.$problem);
            }

            return self::FAILURE;
        }

        $this->info('Snippe iko tayari: config, webhook na API zote zinaonekana sawa.');

        return self::SUCCESS;
    }

    private function webhookBase(string $appUrl): string
    {
        return rtrim((string) config('services.snippe.webhook_base_url', $appUrl), '/');
    }

    private function host(string $url): string
    {
        return (string) (parse_url($url, PHP_URL_HOST) ?: $url);
    }

    private function hostMismatch(string $appUrl, string $webhookUrl): bool
    {
        $appHost = $this->host($appUrl);
        $webhookHost = $this->host($webhookUrl);

        return $appHost !== '' && $webhookHost !== '' && strcasecmp($appHost, $webhookHost) !== 0;
    }
}
