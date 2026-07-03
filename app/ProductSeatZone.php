<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSeatZone extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function seats()
    {
        return $this->hasMany(ProductSeat::class, 'zone_id');
    }
}
