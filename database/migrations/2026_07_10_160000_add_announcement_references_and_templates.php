<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnnouncementReferencesAndTemplates extends Migration
{
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'reference')) {
                $table->string('reference', 64)->nullable()->after('id');
            }
        });

        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'announcement_ref_prefix')) {
                $table->string('announcement_ref_prefix', 32)->default('MGT');
            }
            if (!Schema::hasColumn('general_settings', 'announcement_ref_season')) {
                $table->string('announcement_ref_season', 32)->default('S02');
            }
            if (!Schema::hasColumn('general_settings', 'announcement_ref_next')) {
                $table->unsignedInteger('announcement_ref_next')->default(1);
            }
        });

        if (!Schema::hasTable('announcement_templates')) {
            Schema::create('announcement_templates', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('key', 64)->unique();
                $table->string('name');
                $table->string('subject')->nullable();
                $table->text('header')->nullable();
                $table->longText('body')->nullable();
                $table->text('footer')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('announcement_templates');

        Schema::table('general_settings', function (Blueprint $table) {
            foreach (['announcement_ref_prefix', 'announcement_ref_season', 'announcement_ref_next'] as $col) {
                if (Schema::hasColumn('general_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'reference')) {
                $table->dropColumn('reference');
            }
        });
    }
}
