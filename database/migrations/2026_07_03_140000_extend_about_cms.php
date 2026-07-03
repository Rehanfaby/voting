<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendAboutCms extends Migration
{
    public function up()
    {
        if (Schema::hasTable('about_members') && !Schema::hasColumn('about_members', 'bio')) {
            Schema::table('about_members', function (Blueprint $table) {
                $table->text('bio')->nullable()->after('title');
            });
        }

        if (!Schema::hasTable('about_winners')) {
            Schema::create('about_winners', function (Blueprint $table) {
                $table->increments('id');
                $table->string('year', 8)->default('2025');
                $table->string('placement', 32);
                $table->string('name')->nullable();
                $table->text('bio')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
                $table->unique(['year', 'placement']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('about_winners');

        if (Schema::hasTable('about_members') && Schema::hasColumn('about_members', 'bio')) {
            Schema::table('about_members', function (Blueprint $table) {
                $table->dropColumn('bio');
            });
        }
    }
}
