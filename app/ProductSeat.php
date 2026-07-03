<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSeat extends Model
{
    protected $guarded = [];

    public function zone()
    {
        return $this->belongsTo(ProductSeatZone::class, 'zone_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
