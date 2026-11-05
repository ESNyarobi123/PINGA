<?php

namespace App\Console\Commands;

use App\Exceptions\SubscriptionPurchaseNotAllowedException;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SnippePaymentService;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReconcilePendingSubscriptions extends Command
{
    protected $signature = 'subscriptions:reconcile
                            {--grace=2 : Minutes to wait before polling a pending row}
                            {--expire-after=30 : Minutes before marking a pending row as failed}';

    protected $description = 'Poll Snippe for pending subscription payments and activate the ones that were actually paid.';

    public function handle(SnippePaymentService $snippe, SubscriptionService $subscriptions): int
    {
        $grace = max(1, (int) $this->option('grace'));
        $expireAfter = max($grace, (int) $this->option('expire-after'));

        $pendingRows = Subscription::query()
            ->where('payment_status', 'pending')
            ->where('created_at', '<=', now()->subMinutes($grace))
            ->get();

        if ($pendingRows->isEmpty()) {
            return self::SUCCESS;
        }

        foreach ($pendingRows as $row) {
            if ($row->created_at->lte(now()->subMinutes($expireAfter))) {
                $row->update([
                    'payment_status' => 'failed',
                    'notes' => 'Timed out waiting for Snippe confirmation',
                ]);

                continue;
            }

            $reference = $this->resolvePollingReference($row);

            if (! $reference) {
                continue;
            }

            $status = $snippe->checkPaymentStatus($reference);

            if (! $status) {
                continue;
            }

            $state = $status['status'] ?? null;

            if (in_array($state, ['completed', 'success', 'successful'], true)) {
                $this->activatePaidRow($row, $status['reference'] ?? null, $subscriptions);

                continue;
            }

            if (in_array($state, ['failed', 'cancelled', 'expired', 'rejected'], true)) {
                $row->update([
                    'payment_status' => 'failed',
                    'notes' => 'Snippe status: '.$state,
                ]);
            }
        }

        return self::SUCCESS;
    }

    private function resolvePollingReference(Subscription $row): ?string
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('subscriptions', 'provider_reference')
            && filled($row->provider_reference)) {
            return (string) $row->provider_reference;
        }

        if (is_string($row->notes) && preg_match('/provider_reference=([^\s|]+)/', $row->notes, $matches)) {
            return $matches[1];
        }

        return $row->payment_reference ? (string) $row->payment_reference : null;
    }

    private function activatePaidRow(Subscription $row, ?string $reference, SubscriptionService $subscriptions): void
    {
        $user = User::find($row->user_id);
        $plan = $row->subscriptionPlan()->first()
            ?? SubscriptionPlan::where('slug', $row->plan_slug)->first();

        if (! $user || ! $plan) {
            Log::error('Subscription reconcile: missing user or plan', ['subscription_id' => $row->id]);

            return;
        }

        $activationReference = $reference ?: $this->resolvePollingReference($row) ?: (string) $row->payment_reference;

        try {
            DB::transaction(function () use ($user, $plan, $row, $activationReference, $subscriptions) {
                $subscriptions->activate($user, $plan, $activationReference, 'snippe');

                $row->update([
                    'payment_status' => 'completed',
                    'notes' => 'Reconciled via status polling',
                ]);
            });

            Log::info('Subscription reconciled via polling', [
                'user_id' => $user->id,
                'plan' => $plan->slug,
                'reference' => $activationReference,
            ]);
        } catch (SubscriptionPurchaseNotAllowedException $e) {
            $row->update(['notes' => 'Paid but activation blocked: '.$e->getMessage()]);

            Log::error('Subscription reconcile: activation blocked', [
                'subscription_id' => $row->id,
                'error' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            Log::error('Subscription reconcile error: '.$e->getMessage(), ['subscription_id' => $row->id]);
        }
    }
}
