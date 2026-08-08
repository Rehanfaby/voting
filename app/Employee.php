<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable =[
        "name", "image", "department_id", "email", "phone_number",
        "user_id", "address", "city", "country", "is_active", "is_approve", "is_eliminate"
    ];

    /**
     * Contestants shown on the public site (Vote Now, home, profiles).
     * Soft-deleted and grading-eliminated contestants are excluded.
     */
    public function scopePubliclyListed($query)
    {
        return $query->where('is_active', true)
            ->where('is_approve', true)
            ->where(function ($q) {
                $q->whereNull('is_eliminate')
                    ->orWhere('is_eliminate', 0)
                    ->orWhere('is_eliminate', false);
            });
    }

    public function payroll()
    {
    	return $this->hasMany('App\Payroll');
    }

    public function departments()
    {
        return $this->belongsTo('App\Department', 'department_id', 'id');
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

}
