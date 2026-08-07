<?php

namespace App\Console\Commands;

use App\Helpers\VoteSettings;
use Illuminate\Console\Command;

class ApplySettingSchedules extends Command
{
    protected $signature = 'settings:apply-schedules';

    protected $description = 'Apply Hide Votes / Enable Voting / Grading schedule windows to general_settings flags';

    public function handle()
    {
        $result = VoteSettings::applySchedules();

        if ($result['changed']) {
            $parts = [];
            foreach ($result['updates'] as $col => $val) {
                if ($col === 'updated_at') {
                    continue;
                }
                $parts[] = $col . '=' . $val;
            }
            $this->info('Updated: ' . implode(', ', $parts));
            return 0;
        }

        $this->info('No schedule changes.');
        return 0;
    }
}
