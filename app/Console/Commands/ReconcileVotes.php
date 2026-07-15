<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReconcileVotes extends Command
{
    /**
     * @var string
     */
    protected $signature = 'votes:reconcile {--days=3}';

    /**
     * @var string
     */
    protected $description = 'Re-check pending mobile-money votes against Campay so debited voters are always counted (safety net for dropped connections / missed webhooks).';

    public function handle()
    {
        $days = (int) $this->option('days');

        try {
            $home = new HomeController();
            $result = $home->reconcilePendingVotes($days);
        } catch (\Throwable $e) {
            Log::error('votes:reconcile failed: ' . $e->getMessage());
            $this->error('Reconcile failed: ' . $e->getMessage());
            return 1;
        }

        $summary = sprintf(
            'Vote reconcile — checked: %d, confirmed: %d, failed: %d%s',
            $result['checked'] ?? 0,
            $result['confirmed'] ?? 0,
            $result['failed'] ?? 0,
            isset($result['note']) ? ' (' . $result['note'] . ')' : ''
        );

        if (($result['confirmed'] ?? 0) > 0 || ($result['failed'] ?? 0) > 0) {
            Log::info($summary);
        }
        $this->info($summary);

        return 0;
    }
}
