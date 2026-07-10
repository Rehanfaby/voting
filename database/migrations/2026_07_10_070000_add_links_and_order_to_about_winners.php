<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinksAndOrderToAboutWinners extends Migration
{
    public function up()
    {
        Schema::table('about_winners', function (Blueprint $table) {
            if (!Schema::hasColumn('about_winners', 'label')) {
                $table->string('label')->nullable()->after('placement');
            }
            if (!Schema::hasColumn('about_winners', 'links')) {
                $table->text('links')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('about_winners', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('links');
            }
        });
    }

    public function down()
    {
        Schema::table('about_winners', function (Blueprint $table) {
            foreach (['label', 'links', 'sort_order'] as $col) {
                if (Schema::hasColumn('about_winners', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
