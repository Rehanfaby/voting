<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddHallSeatingPermissions extends Migration
{
    public function up()
    {
        $perms = [
            'halls-index',
            'halls-edit',
            'hall-layouts-publish',
            'event-seat-maps-manage',
        ];

        foreach ($perms as $name) {
            if (!Permission::where('name', $name)->exists()) {
                Permission::create(['name' => $name, 'guard_name' => 'web']);
            }
        }

        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            foreach ($perms as $name) {
                $p = Permission::where('name', $name)->first();
                if ($p && !DB::table('role_has_permissions')
                    ->where('permission_id', $p->id)
                    ->where('role_id', $admin->id)
                    ->exists()) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $p->id,
                        'role_id' => $admin->id,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $perms = ['halls-index', 'halls-edit', 'hall-layouts-publish', 'event-seat-maps-manage'];
        $ids = Permission::whereIn('name', $perms)->pluck('id');
        if ($ids->count()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $ids)->delete();
            Permission::whereIn('id', $ids)->delete();
        }
    }
}
