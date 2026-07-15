<?php

namespace App\Services\Payments\Data;

class PaymentRequestData
{
    /** @var string */
    public $payableType;

    /** @var int */
    public $payableId;

    /** @var int */
    public $amount;

    /** @var string */
    public $currency;

    /** @var string */
    public $phoneNumber;

    /** @var string */
    public $network;

    /** @var string */
    public $description;

    /** @var string */
    public $clientReferenceId;

    /** @var int|null */
    public $userId;

    /** @var string|null */
    public $providerTransactionId;

    /** @var string|null */
    public $publicReference;

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
