<?php

namespace App\Services\Payments\Data;

class PaymentCallbackResult
{
    /** @var bool */
    public $handled = false;

    /** @var string|null */
    public $status;

    /** @var string|null */
    public $providerStatus;

    /** @var string|null */
    public $providerTransactionId;

    /** @var string|null */
    public $providerReference;

    /** @var int|null */
    public $amount;

    /** @var string|null */
    public $currency;

    /** @var string|null */
    public $mobileNetwork;

    /** @var string|null */
    public $failureCode;

    /** @var string|null */
    public $failureMessage;

    /** @var bool */
    public $requiresManualReview = false;

    /** @var array */
    public $rawPayload = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
