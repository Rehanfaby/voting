<?php

namespace App\Console\Commands;

use App\Services\JudgeAmbassadorAccountService;
use Illuminate\Console\Command;

class SyncJudgeAmbassadorAccounts extends Command
{
    protected $signature = 'users:link-judge-ambassador
                            {--reset-password : Generate a new password for each linked account}
                            {--type=all : ambassadors|judges|all}';

    protected $description = 'Link Ambassador/Judge profiles to login users with the correct roles';

    public function handle(JudgeAmbassadorAccountService $service)
    {
        $reset = (bool) $this->option('reset-password');
        $type = strtolower((string) $this->option('type'));

        $results = [];
        if (in_array($type, ['all', 'ambassadors', 'ambassador'], true)) {
            $results = array_merge($results, $service->syncAllAmbassadors($reset));
        }
        if (in_array($type, ['all', 'judges', 'judge'], true)) {
            $results = array_merge($results, $service->syncAllJudges($reset));
        }

        if (empty($results)) {
            $this->warn('No profiles processed.');
            return 0;
        }

        $rows = [];
        foreach ($results as $row) {
            $user = $row['user'];
            $rows[] = [
                $row['type'],
                $row['name'],
                $user->id,
                $user->email,
                $user->phone,
                $user->role_id,
                $row['created'] ? 'created' : 'linked',
                $row['password'] ?: '—',
            ];
        }

        $this->table(
            ['Type', 'Profile', 'User ID', 'Email', 'Phone', 'Role', 'Status', 'Password'],
            $rows
        );

        $this->info('Done. ' . count($results) . ' profile(s) processed.');
        if ($reset) {
            $this->warn('Temporary passwords were generated. Share them securely, then ask users to change via Forgot Password.');
        } else {
            $this->line('Existing passwords were kept. Users can sign in with name, email, or phone, or use Forgot Password.');
        }

        return 0;
    }
}
