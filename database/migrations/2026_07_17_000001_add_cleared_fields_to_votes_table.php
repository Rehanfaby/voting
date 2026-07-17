<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClearedFieldsToVotesTable extends Migration
{
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'cleared_at')) {
                $table->timestamp('cleared_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('votes', 'cleared_vote')) {
                $table->unsignedInteger('cleared_vote')->nullable()->after('cleared_at');
            }
        });
    }

    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            if (Schema::hasColumn('votes', 'cleared_vote')) {
                $table->dropColumn('cleared_vote');
            }
            if (Schema::hasColumn('votes', 'cleared_at')) {
                $table->dropColumn('cleared_at');
            }
        });
    }
}
