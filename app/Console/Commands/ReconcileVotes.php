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
    protected $signature = 'votes:reconcile {--days=14} {--phone= : Optional phone filter (e.g. 698677954)}';

    /**
     * @var string
     */
    protected $description = 'Re-check pending mobile-money votes against Campay so debited voters are always counted (safety net for dropped connections / missed webhooks).';

    public function handle()
    {
        @set_time_limit(0);
        $days = (int) $this->option('days');
        $phone = $this->option('phone');

        try {
            $home = app(HomeController::class);
            $result = $home->reconcilePendingVotes($days, $phone);
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

        // Always log + heartbeat so Hostinger cron can be verified even when nothing changed.
        Log::info($summary);
        @file_put_contents(storage_path('app/votes-reconcile.heartbeat'), json_encode([
            'at' => date('Y-m-d H:i:s'),
            'result' => $result,
        ]) . PHP_EOL, FILE_APPEND);
        $this->info($summary);

        return 0;
    }
}
