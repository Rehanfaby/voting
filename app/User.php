<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'name', 'email', 'password',"phone","company_name", "role_id", "biller_id", "warehouse_id", "is_active", "is_deleted", "sign", "stemp", "otp", "otp_time", "otp_verify", "whatsapp_number"
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isActive()
    {
        return $this->is_active;
    }

    public function holiday() {
        return $this->hasMany('App\Holiday');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'judge_id', 'id');
    }

    /** App role row (Admin / Voter / …) via users.role_id — not Spatie's roles() pivot. */
    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }

    public function isAdmin()
    {
        if ((int) $this->role_id === 1) {
            return true;
        }
        $name = optional($this->role)->name;
        return $name && strcasecmp($name, 'Admin') === 0;
    }

}
