<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Employee;

class PurgeStaleContestants extends Command
{
    protected $signature = 'contestants:purge-stale {--force : Skip the confirmation prompt}';

    protected $description = 'Permanently delete inactive/unapproved contestants and their votes, keeping only active approved contestants';

    public function handle()
    {
        $keepIds = Employee::where('is_active', true)->where('is_approve', true)->pluck('id')->toArray();
        $stale = Employee::where(function ($q) {
            $q->where('is_active', false)->orWhere('is_approve', false);
        })->get();

        $staleIds = $stale->pluck('id')->toArray();
        $voteCount = empty($staleIds) ? 0 : DB::table('votes')->whereIn('musician_id', $staleIds)->count();
        $pointCount = empty($staleIds) ? 0 : DB::table('points')->whereIn('candidate_id', $staleIds)->count();
        $galleryCount = empty($staleIds) ? 0 : DB::table('galleries')->whereIn('employee_id', $staleIds)->count();

        $this->line('Keeping active approved contestants: ' . count($keepIds));
        foreach ($keepIds as $id) {
            $e = Employee::find($id);
            if ($e) {
                $this->line('  ✓ ' . $e->name . ' (#' . $id . ')');
            }
        }

        $this->warn('Will permanently delete stale contestants: ' . count($staleIds));
        $this->line("  - votes: {$voteCount}, points: {$pointCount}, galleries: {$galleryCount}");

        if (empty($staleIds)) {
            $this->info('Nothing to delete — only active approved contestants remain.');
            return 0;
        }

        if (!$this->option('force') && !$this->confirm('Proceed with deleting stale contestant data?')) {
            $this->info('Aborted.');
            return 0;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            DB::table('votes')->whereIn('musician_id', $staleIds)->delete();
            DB::table('points')->whereIn('candidate_id', $staleIds)->delete();
            DB::table('galleries')->whereIn('employee_id', $staleIds)->delete();
            Employee::whereIn('id', $staleIds)->delete();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('Done. Stale contestants removed; active approved contestants kept.');
        return 0;
    }
}
