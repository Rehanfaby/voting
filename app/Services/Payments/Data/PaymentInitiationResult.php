<?php

namespace App\Services\Payments\Data;

class PaymentInitiationResult
{
    /** @var bool */
    public $success;

    /** @var string */
    public $status;

    /** @var string|null */
    public $providerStatus;

    /** @var string|null */
    public $providerTransactionId;

    /** @var string|null */
    public $providerReference;

    /** @var string */
    public $message;

    /** @var string|null */
    public $failureCode;

    /** @var string|null */
    public $failureMessage;

    /** @var bool */
    public $uncertain = false;

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

    public function toFrontendArray($transactionReference)
    {
        return [
            'success' => (bool) $this->success,
            'message' => (string) $this->message,
            'transaction_reference' => (string) $transactionReference,
            'status' => (string) $this->status,
            'provider' => null,
            'provider_status' => $this->providerStatus,
        ];
    }
}
