<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocaleToVotesTable extends Migration
{
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'locale')) {
                $table->string('locale', 5)->nullable()->after('whatsapp_number');
            }
        });
    }

    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            if (Schema::hasColumn('votes', 'locale')) {
                $table->dropColumn('locale');
            }
        });
    }
}
