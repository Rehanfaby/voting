<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =[

        "name", 'image', "parent_id", "is_active",
        "countdown_enabled", "countdown_at", "countdown_label",
    ];

    public function hasActiveCountdown()
    {
        return $this->countdown_enabled
            && $this->countdown_at
            && strtotime($this->countdown_at) > time();
    }

    public function countdownDeadlineAttr()
    {
        if (!$this->countdown_at) {
            return '';
        }
        return date('Y-m-d H:i:s', strtotime($this->countdown_at));
    }

    public function product()
    {
    	return $this->hasMany('App\Product');
    }
}
