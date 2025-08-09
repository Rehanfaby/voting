<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketSeat extends Model
{
    protected $table = 'ticket_seats';
    public $timestamps = false;
    protected $guarded = [];

    public function ticket() {
        return $this->belongsTo('App\Ticket');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
