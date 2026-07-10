<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $guarded = [];

    /** Public URL for the partner logo. */
    public function imageUrl()
    {
        return url('public/images/partners', $this->image);
    }
}
