<?php

namespace App\Services\Payments\Data;

class PaymentStatusResult
{
    /** @var string */
    public $status;

    /** @var string|null */
    public $providerStatus;

    /** @var string|null */
    public $providerReference;

    /** @var string|null */
    public $failureCode;

    /** @var string|null */
    public $failureMessage;

    /** @var array */
    public $rawResponse = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
