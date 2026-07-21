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

    public function mobileMoneyPayment()
    {
        return $this->belongsTo(MobileMoneyPayment::class, 'mobile_money_payment_id');
    }

    /** Normalized channel: momo|om|card|null */
    public function resolvedPaymentMethod()
    {
        $method = strtolower(trim((string) ($this->payment_method ?? '')));
        if (in_array($method, ['momo', 'om', 'card'], true)) {
            return $method;
        }

        $provider = strtolower(trim((string) ($this->payment_provider ?? '')));
        $reference = (string) ($this->reference ?? '');
        if ($provider === 'stripe' || strpos($reference, 'pi_') === 0) {
            return 'card';
        }

        $network = strtoupper((string) optional($this->mobileMoneyPayment)->mobile_network);
        if ($network !== '' && strpos($network, 'ORANGE') !== false) {
            return 'om';
        }

        if ($provider !== '' || $this->mobile_money_payment_id) {
            return 'momo';
        }

        return null;
    }

    public function paymentMethodLabel()
    {
        switch ($this->resolvedPaymentMethod()) {
            case 'card':
                return 'Visa / Mastercard';
            case 'om':
                return 'Orange Money';
            case 'momo':
                return 'MTN MoMo';
            default:
                return '—';
        }
    }
}
