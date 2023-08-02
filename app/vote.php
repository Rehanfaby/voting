<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vote extends Model
{
    protected $guarded =[ ];

    public function musicians() {
        return $this->belongsTo('App\Employee', 'musician_id');
    }

    public function voters() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
