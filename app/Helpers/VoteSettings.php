<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

/**
 * Safe reads for voting / approval flags. Never throws — defaults keep the site up
 * even if migrations are pending or the DB is temporarily unavailable.
 */
class VoteSettings
{
    /** @var array<int, array{flag:string,start:string,end:string}> */
    protected static $scheduleMap = [
        ['flag' => 'hide_votes', 'start' => 'hide_votes_starts_at', 'end' => 'hide_votes_ends_at'],
        ['flag' => 'is_voting_start', 'start' => 'voting_starts_at', 'end' => 'voting_ends_at'],
        ['flag' => 'available_grading', 'start' => 'grading_starts_at', 'end' => 'grading_ends_at'],
    ];

    /** @var bool */
    protected static $schedulesAppliedThisRequest = false;

    /** @var object|null|false false = not loaded yet */
    protected static $settingsRowCache = false;

    protected static function settingsRow()
    {
        if (self::$settingsRowCache !== false) {
            return self::$settingsRowCache;
        }

        try {
            if (!Schema::hasTable('general_settings')) {
                return self::$settingsRowCache = null;
            }
            return self::$settingsRowCache = DB::table('general_settings')->orderByDesc('id')->first();
        } catch (\Throwable $e) {
            return self::$settingsRowCache = null;
        }
    }

    /**
     * Sync schedule windows into DB flags once per HTTP/console request.
     * Needed because Hostinger may not run `php artisan schedule:run`.
     */
    public static function ensureSchedulesApplied()
    {
        if (self::$schedulesAppliedThisRequest) {
            return;
        }
        self::$schedulesAppliedThisRequest = true;
        self::applySchedules();
    }

    protected static function flag($column, $default = false)
    {
        try {
            if (!Schema::hasColumn('general_settings', $column)) {
                return $default;
            }
            // Keep public site + admin checkboxes in sync with windows even without cron.
            if (in_array($column, ['hide_votes', 'is_voting_start', 'available_grading'], true)) {
                self::ensureSchedulesApplied();
            }
            $gs = self::settingsRow();
            if (!$gs || !isset($gs->{$column})) {
                return $default;
            }
            return (bool) $gs->{$column};
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /** Whether vote counts should be shown on the public site. */
    public static function showPublicCounts()
    {
        if (self::flag('hide_votes', false)) {
            return false;
        }

        try {
            $role = Role::first();
            return $role && $role->hasPermissionTo('see-votes');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /** Whether new contestants must be approved before going live. */
    public static function requireContestantApproval()
    {
        return self::flag('require_contestant_approval', true);
    }

    /** is_approve value for a newly created contestant profile. */
    public static function initialContestantApproval()
    {
        return self::requireContestantApproval() ? false : true;
    }

    /** Whether public voting buttons should appear / votes may be submitted. */
    public static function votingEnabled()
    {
        return self::flag('is_voting_start', false);
    }

    /** Whether Judge / Ambassador grading may be saved. */
    public static function gradingEnabled()
    {
        return self::flag('available_grading', false);
    }

    /**
     * Sync boolean flags from configured start/end windows.
     * When both ends of a window are set: flag = (start <= now <= end).
     * When either end is blank: leave the flag unchanged (manual control).
     *
     * @return array{changed:bool,updates:array<string,int>}
     */
    public static function applySchedules(?Carbon $now = null)
    {
        $result = ['changed' => false, 'updates' => []];

        try {
            if (!Schema::hasTable('general_settings')) {
                return $result;
            }

            $gs = DB::table('general_settings')->orderByDesc('id')->first();
            if (!$gs) {
                return $result;
            }

            $now = $now ?: Carbon::now(config('app.timezone'));
            $updates = [];

            foreach (self::$scheduleMap as $map) {
                $flagCol = $map['flag'];
                $startCol = $map['start'];
                $endCol = $map['end'];

                if (!Schema::hasColumn('general_settings', $flagCol)
                    || !Schema::hasColumn('general_settings', $startCol)
                    || !Schema::hasColumn('general_settings', $endCol)) {
                    continue;
                }

                $startRaw = $gs->{$startCol} ?? null;
                $endRaw = $gs->{$endCol} ?? null;
                if (empty($startRaw) || empty($endRaw)) {
                    continue;
                }

                try {
                    $start = Carbon::parse($startRaw, config('app.timezone'));
                    $end = Carbon::parse($endRaw, config('app.timezone'));
                } catch (\Throwable $e) {
                    continue;
                }

                $desired = ($now->greaterThanOrEqualTo($start) && $now->lessThanOrEqualTo($end)) ? 1 : 0;
                $current = (int) ((bool) ($gs->{$flagCol} ?? 0));
                if ($current !== $desired) {
                    $updates[$flagCol] = $desired;
                }
            }

            if (!empty($updates)) {
                $updates['updated_at'] = Carbon::now();
                DB::table('general_settings')->where('id', $gs->id)->update($updates);
                AppCache::forgetSharedData();
                $result['changed'] = true;
                $result['updates'] = $updates;
            }
        } catch (\Throwable $e) {
            return $result;
        }

        return $result;
    }

    /**
     * Whether a schedule window is fully configured for a flag group.
     *
     * @param  object|null  $gs
     */
    public static function scheduleActive($gs, $startCol, $endCol)
    {
        if (!$gs) {
            return false;
        }
        return !empty($gs->{$startCol}) && !empty($gs->{$endCol});
    }

    /**
     * Human-readable effective state for the admin UI.
     *
     * @param  object|null  $gs
     * @return string|null
     */
    public static function scheduleStatusLabel($gs, $flagCol, $startCol, $endCol)
    {
        if (!self::scheduleActive($gs, $startCol, $endCol)) {
            return null;
        }

        try {
            $now = Carbon::now(config('app.timezone'));
            $start = Carbon::parse($gs->{$startCol}, config('app.timezone'));
            $end = Carbon::parse($gs->{$endCol}, config('app.timezone'));
            $on = $now->greaterThanOrEqualTo($start) && $now->lessThanOrEqualTo($end);
            $fmt = 'Y-m-d H:i';
            if ($on) {
                return 'Scheduled: ON until ' . $end->format($fmt);
            }
            if ($now->lt($start)) {
                return 'Scheduled: OFF — turns ON ' . $start->format($fmt);
            }
            return 'Scheduled: OFF (window ended ' . $end->format($fmt) . ')';
        } catch (\Throwable $e) {
            return 'Scheduled: active (flag follows window)';
        }
    }

    /**
     * Parse a datetime-local form value into a DB datetime string, or null if blank.
     */
    public static function parseScheduleInput($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        try {
            return Carbon::parse($value, config('app.timezone'))->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Format a stored datetime for a datetime-local input. */
    public static function forDatetimeLocal($value)
    {
        if (empty($value)) {
            return '';
        }
        try {
            return Carbon::parse($value, config('app.timezone'))->format('Y-m-d\TH:i');
        } catch (\Throwable $e) {
            return '';
        }
    }
}
