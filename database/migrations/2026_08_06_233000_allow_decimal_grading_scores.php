<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AllowDecimalGradingScores extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ambassador_points') && Schema::hasColumn('ambassador_points', 'points')) {
            DB::statement('ALTER TABLE ambassador_points MODIFY points DECIMAL(8,2) NOT NULL DEFAULT 0');
        }

        if (Schema::hasTable('points')) {
            $cols = [
                'depth', 'diction', 'accuracy', 'interpretation', 'technique',
                'stage_presence', 'song_choice', 'overall_presentation',
                'adaptability', 'audience_interaction', 'total',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('points', $col)) {
                    DB::statement("ALTER TABLE points MODIFY {$col} DECIMAL(8,2) NOT NULL DEFAULT 0");
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('ambassador_points') && Schema::hasColumn('ambassador_points', 'points')) {
            DB::statement('ALTER TABLE ambassador_points MODIFY points INT NOT NULL DEFAULT 0');
        }

        if (Schema::hasTable('points')) {
            $cols = [
                'depth', 'diction', 'accuracy', 'interpretation', 'technique',
                'stage_presence', 'song_choice', 'overall_presentation',
                'adaptability', 'audience_interaction', 'total',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('points', $col)) {
                    DB::statement("ALTER TABLE points MODIFY {$col} INT NOT NULL DEFAULT 0");
                }
            }
        }
    }
}
