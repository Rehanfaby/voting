<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoteAndApprovalFlagsToGeneralSettings extends Migration
{
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'hide_votes')) {
                $table->boolean('hide_votes')->default(false);
            }
            if (!Schema::hasColumn('general_settings', 'require_contestant_approval')) {
                $table->boolean('require_contestant_approval')->default(true);
            }
        });
    }

    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (Schema::hasColumn('general_settings', 'hide_votes')) {
                $table->dropColumn('hide_votes');
            }
            if (Schema::hasColumn('general_settings', 'require_contestant_approval')) {
                $table->dropColumn('require_contestant_approval');
            }
        });
    }
}
