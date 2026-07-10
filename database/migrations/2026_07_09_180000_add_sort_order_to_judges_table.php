<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderToJudgesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('judges', 'sort_order')) {
            Schema::table('judges', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('judges', 'sort_order')) {
            Schema::table('judges', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
}
