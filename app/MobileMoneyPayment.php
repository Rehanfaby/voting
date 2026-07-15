<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileMoneyPayment extends Model
{
    protected $guarded = [];

    protected $dates = [
        'completed_at',
        'failed_at',
        'last_status_checked_at',
        'created_at',
        'updated_at',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted()
    {
        return $this->status === \App\Services\Payments\Enums\PaymentStatus::COMPLETED;
    }

    public function isFinal()
    {
        return \App\Services\Payments\Enums\PaymentStatus::isFinal($this->status);
    }
}
