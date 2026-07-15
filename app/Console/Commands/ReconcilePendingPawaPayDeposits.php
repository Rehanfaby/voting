<?php

namespace App\Console\Commands;

use App\Services\Payments\Enums\PaymentStatus;
use App\Services\Payments\VoteMobileMoneyService;
use App\vote;
use Illuminate\Console\Command;

class ReconcilePendingPawaPayDeposits extends Command
{
    protected $signature = 'payments:reconcile-pawapay {--days=3}';

    protected $description = 'Reconcile pending PawaPay mobile money deposits';

    public function handle(VoteMobileMoneyService $voteMobileMoneyService)
    {
        $stats = $voteMobileMoneyService->reconcilePendingPayments((int) $this->option('days'));

        $confirmedVotes = 0;
        $failedVotes = 0;

        $payments = \App\MobileMoneyPayment::where('provider', 'pawapay')
            ->whereIn('status', [PaymentStatus::COMPLETED, PaymentStatus::FAILED])
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->get();

        $home = app(\App\Http\Controllers\HomeController::class);
        foreach ($payments as $payment) {
            if ($payment->payable_type !== vote::class) {
                continue;
            }

            if ($payment->status === PaymentStatus::COMPLETED) {
                if ($home->markVoteSuccessfulPublic($payment->payable_id, $payment->provider_reference ?: $payment->provider_transaction_id)) {
                    $confirmedVotes++;
                }
            } elseif ($payment->status === PaymentStatus::FAILED) {
                $home->markVoteFailedPublic($payment->payable_id);
                $failedVotes++;
            }
        }

        $this->info(sprintf(
            'Checked %d payments, confirmed %d payments / %d votes, failed %d payments / %d votes',
            $stats['checked'],
            $stats['confirmed'],
            $confirmedVotes,
            $stats['failed'],
            $failedVotes
        ));

        return 0;
    }
}
