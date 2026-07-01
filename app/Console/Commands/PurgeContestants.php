<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Employee;
use App\User;

class PurgeContestants extends Command
{
    protected $signature = 'contestants:purge {--force : Skip the confirmation prompt}';

    protected $description = 'Permanently delete ALL contestants (employees + their linked users) and their votes/points/gallery so registration can start fresh';

    public function handle()
    {
        $employees = Employee::count();
        $contestantUsers = User::where('role_id', 2)->count();
        $votes = DB::table('votes')->count();
        $points = DB::table('points')->count();
        $galleries = DB::table('galleries')->count();

        $this->warn('This will PERMANENTLY delete:');
        $this->line("  - employees: {$employees}");
        $this->line("  - contestant users (role_id = 2): {$contestantUsers}");
        $this->line("  - votes: {$votes}");
        $this->line("  - points: {$points}");
        $this->line("  - galleries: {$galleries}");

        if (!$this->option('force') && !$this->confirm('Proceed with the purge?')) {
            $this->info('Aborted. Nothing was deleted.');
            return 0;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            DB::table('votes')->delete();
            DB::table('points')->delete();
            DB::table('galleries')->delete();
            Employee::query()->delete();
            User::where('role_id', 2)->delete();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('Done. All contestants and their related data have been purged. Registration is now clean.');
        return 0;
    }
}
