<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingScheduleWindowsToGeneralSettings extends Migration
{
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'hide_votes_starts_at')) {
                $table->timestamp('hide_votes_starts_at')->nullable()->after('hide_votes');
            }
            if (!Schema::hasColumn('general_settings', 'hide_votes_ends_at')) {
                $table->timestamp('hide_votes_ends_at')->nullable()->after('hide_votes_starts_at');
            }
            if (!Schema::hasColumn('general_settings', 'voting_starts_at')) {
                $table->timestamp('voting_starts_at')->nullable()->after('is_voting_start');
            }
            if (!Schema::hasColumn('general_settings', 'voting_ends_at')) {
                $table->timestamp('voting_ends_at')->nullable()->after('voting_starts_at');
            }
            if (!Schema::hasColumn('general_settings', 'grading_starts_at')) {
                $table->timestamp('grading_starts_at')->nullable()->after('available_grading');
            }
            if (!Schema::hasColumn('general_settings', 'grading_ends_at')) {
                $table->timestamp('grading_ends_at')->nullable()->after('grading_starts_at');
            }
        });
    }

    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            foreach ([
                'hide_votes_starts_at',
                'hide_votes_ends_at',
                'voting_starts_at',
                'voting_ends_at',
                'grading_starts_at',
                'grading_ends_at',
            ] as $column) {
                if (Schema::hasColumn('general_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
