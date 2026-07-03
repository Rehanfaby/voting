<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventCountdownToCategories extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'countdown_enabled')) {
                $table->boolean('countdown_enabled')->default(false);
            }
            if (!Schema::hasColumn('categories', 'countdown_at')) {
                $table->dateTime('countdown_at')->nullable();
            }
            if (!Schema::hasColumn('categories', 'countdown_label')) {
                $table->string('countdown_label')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['countdown_enabled', 'countdown_at', 'countdown_label']);
        });
    }
}
