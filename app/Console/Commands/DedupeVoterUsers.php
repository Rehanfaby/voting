<?php

namespace App\Console\Commands;

use App\Helpers\PhoneHelper;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DedupeVoterUsers extends Command
{
    protected $signature = 'voters:dedupe {--dry-run : Show duplicates without changing data}';

    protected $description = 'Merge duplicate voter accounts that share the same phone so one person is one voter';

    public function handle()
    {
        $dry = (bool) $this->option('dry-run');
        $voters = User::where('role_id', 3)->where('is_deleted', false)->orderBy('id')->get();
        $groups = [];
        foreach ($voters as $user) {
            $key = PhoneHelper::identityKey($user->phone ?: $user->whatsapp_number);
            if (!$key) {
                continue;
            }
            $groups[$key][] = $user;
        }

        $merged = 0;
        foreach ($groups as $key => $users) {
            if (count($users) < 2) {
                continue;
            }
            $keeper = $this->pickKeeper($users);
            $extras = array_values(array_filter($users, function ($u) use ($keeper) {
                return (int) $u->id !== (int) $keeper->id;
            }));
            $extraIds = array_map(function ($u) {
                return $u->id;
            }, $extras);
            $this->line(($dry ? '[dry] ' : '') . "phone +{$key} keep={$keeper->id} merge=" . implode(',', $extraIds));

            if ($dry) {
                $merged += count($extras);
                continue;
            }

            DB::transaction(function () use ($keeper, $extras, $key) {
                $normalized = '+' . $key;
                foreach ($extras as $extra) {
                    $this->reassignUserId('votes', $extra->id, $keeper->id);
                    $this->reassignUserId('tickets', $extra->id, $keeper->id);
                    $this->reassignUserId('mobile_money_payments', $extra->id, $keeper->id);
                    $extra->is_deleted = true;
                    $extra->is_active = false;
                    $extra->save();
                }
                if ($keeper->phone !== $normalized) {
                    $keeper->phone = $normalized;
                    $keeper->save();
                }
            });
            $merged += count($extras);
        }

        $this->info(($dry ? 'Would merge ' : 'Merged ') . $merged . ' duplicate voter account(s).');

        return 0;
    }

    private function pickKeeper(array $users)
    {
        $best = $users[0];
        $bestVotes = -1;
        foreach ($users as $user) {
            $votes = (int) DB::table('votes')->where('user_id', $user->id)->where('status', 1)->sum('vote');
            if ($votes > $bestVotes || ($votes === $bestVotes && (int) $user->id < (int) $best->id)) {
                $best = $user;
                $bestVotes = $votes;
            }
        }

        return $best;
    }

    private function reassignUserId(string $table, $fromId, $toId): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'user_id')) {
            return;
        }
        DB::table($table)->where('user_id', $fromId)->update(['user_id' => $toId]);
    }
}
