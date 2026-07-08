<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixAdminCameroonPhone extends Migration
{
    public function up()
    {
        DB::table('users')
            ->where('id', 1)
            ->update([
                'phone' => '+237675321739',
                'whatsapp_number' => '+237675321739',
                'additional_phone' => null,
            ]);
    }

    public function down()
    {
        // No rollback — Pakistan test number should not be restored.
    }
}
