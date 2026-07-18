<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use App\Services\Payments\VoteMobileMoneyService;
use App\vote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Background poller: keeps checking Campay/PawaPay after the voter leaves the page.
 */
class WatchVotePaymentCommand extends Command
{
    protected $signature = 'votes:watch
        {id : Vote id}
        {--seconds=8 : Seconds between status checks}
        {--max=100 : Max checks (~13 min at 8s)}';

    protected $description = 'Watch a pending mobile-money vote until paid/failed (does not depend on the browser)';

    public function handle(VoteMobileMoneyService $service)
    {
        @set_time_limit(0);
        ignore_user_abort(true);

        $voteId = (int) $this->argument('id');
        $seconds = max(3, (int) $this->option('seconds'));
        $max = max(1, (int) $this->option('max'));
        $home = app(HomeController::class);

        for ($i = 0; $i < $max; $i++) {
            $vote = vote::find($voteId);
            if (!$vote) {
                $this->error('Vote not found');
                return 1;
            }

            if ((int) $vote->status === HomeController::VOTE_SUCCESS) {
                $this->info("Vote #{$voteId} already successful");
                return 0;
            }
            if ((int) $vote->status === HomeController::VOTE_FAILED) {
                $this->info("Vote #{$voteId} already failed");
                return 0;
            }

            try {
                $status = $service->refreshVoteStatus($vote);
            } catch (\Throwable $e) {
                Log::warning('votes:watch status check failed', [
                    'vote_id' => $voteId,
                    'error' => $e->getMessage(),
                ]);
                $status = 'PENDING';
            }

            if ($status === 'SUCCESSFUL') {
                $home->markVoteSuccessfulPublic($voteId, $vote->reference);
                Log::info('votes:watch confirmed vote', ['vote_id' => $voteId, 'reference' => $vote->reference]);
                $this->info("Vote #{$voteId} confirmed");
                return 0;
            }

            if ($status === 'FAILED') {
                $home->markVoteFailedPublic($voteId);
                Log::info('votes:watch marked failed', ['vote_id' => $voteId]);
                $this->info("Vote #{$voteId} failed");
                return 0;
            }

            sleep($seconds);
        }

        $this->warn("Vote #{$voteId} still pending after {$max} checks (reconcile cron will keep trying)");
        return 0;
    }
}
