<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

/**
 * Safe reads for voting / approval flags. Never throws — defaults keep the site up
 * even if migrations are pending or the DB is temporarily unavailable.
 */
class VoteSettings
{
    protected static function settingsRow()
    {
        try {
            if (!Schema::hasTable('general_settings')) {
                return null;
            }
            return DB::table('general_settings')->latest()->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected static function flag($column, $default = false)
    {
        try {
            if (!Schema::hasColumn('general_settings', $column)) {
                return $default;
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

    /** Whether public voting buttons should appear. */
    public static function votingEnabled()
    {
        return self::flag('is_voting_start', false);
    }
}
