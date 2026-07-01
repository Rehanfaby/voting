<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InspectEmail extends Command
{
    protected $signature = 'email:inspect {email} {--free : Soft-delete matching users/employees to free the email}';

    protected $description = 'Show where an email/name is used in users and employees tables (and optionally free it)';

    public function handle()
    {
        $email = trim($this->argument('email'));

        $users = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [strtolower($email)])
            ->get(['id', 'name', 'email', 'role_id', 'is_deleted', 'is_active']);

        $this->info("users matching '{$email}': " . count($users));
        foreach ($users as $u) {
            $this->line("  #{$u->id} name={$u->name} role_id={$u->role_id} is_deleted={$u->is_deleted} is_active={$u->is_active}");
        }

        $emps = DB::table('employees')
            ->whereRaw('LOWER(email) = ?', [strtolower($email)])
            ->get(['id', 'name', 'email', 'is_active', 'user_id']);

        $this->info("employees matching '{$email}': " . count($emps));
        foreach ($emps as $e) {
            $this->line("  #{$e->id} name={$e->name} is_active={$e->is_active} user_id={$e->user_id}");
        }

        if ($this->option('free')) {
            DB::table('users')
                ->whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->update(['is_deleted' => 1, 'is_active' => 0]);
            DB::table('employees')
                ->whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->update(['is_active' => 0]);
            $this->info("Freed '{$email}': matching users/employees were disabled.");
        }

        return 0;
    }
}
