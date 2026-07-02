<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class VoteSettings
{
    /** Whether vote counts should be shown on the public site. */
    public static function showPublicCounts()
    {
        $gs = DB::table('general_settings')->latest()->first();
        if ($gs && !empty($gs->hide_votes)) {
            return false;
        }

        $role = Role::first();
        return $role && $role->hasPermissionTo('see-votes');
    }

    /** Whether new contestants must be approved before going live. */
    public static function requireContestantApproval()
    {
        $gs = DB::table('general_settings')->latest()->first();
        if (!$gs) {
            return true;
        }

        return !isset($gs->require_contestant_approval) || (bool) $gs->require_contestant_approval;
    }

    /** is_approve value for a newly created contestant profile. */
    public static function initialContestantApproval()
    {
        return self::requireContestantApproval() ? false : true;
    }
}
