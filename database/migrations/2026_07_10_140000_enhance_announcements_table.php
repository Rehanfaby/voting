<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceAnnouncementsTable extends Migration
{
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'recipients_json')) {
                $table->longText('recipients_json')->nullable()->after('cc');
            }
            if (!Schema::hasColumn('announcements', 'schedules_json')) {
                $table->longText('schedules_json')->nullable()->after('recipients_json');
            }
            if (!Schema::hasColumn('announcements', 'reminders_json')) {
                $table->longText('reminders_json')->nullable()->after('schedules_json');
            }
            if (!Schema::hasColumn('announcements', 'status')) {
                $table->string('status', 32)->default('draft')->after('is_sent');
            }
            if (!Schema::hasColumn('announcements', 'audience_category')) {
                $table->string('audience_category', 32)->nullable()->after('people_type');
            }
        });
    }

    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            foreach (['recipients_json', 'schedules_json', 'reminders_json', 'status'] as $col) {
                if (Schema::hasColumn('announcements', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
