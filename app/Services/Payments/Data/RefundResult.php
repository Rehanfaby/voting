<?php

namespace App\Services\Payments\Data;

class RefundResult
{
    /** @var bool */
    public $success = false;

    /** @var string */
    public $status = 'failed';

    /** @var string|null */
    public $providerStatus;

    /** @var string|null */
    public $providerRefundId;

    /** @var string */
    public $message = '';

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
