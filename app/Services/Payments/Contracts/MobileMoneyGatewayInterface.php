<?php

namespace App\Services\Payments\Contracts;

use App\Services\Payments\Data\PaymentCallbackResult;
use App\Services\Payments\Data\PaymentInitiationResult;
use App\Services\Payments\Data\PaymentRequestData;
use App\Services\Payments\Data\PaymentStatusResult;
use App\Services\Payments\Data\RefundResult;

interface MobileMoneyGatewayInterface
{
    public function initiateDeposit(PaymentRequestData $data);

    public function getTransactionStatus($providerTransactionId);

    public function processCallback(array $payload, array $headers = []);

    public function refund($providerTransactionId, $amount = null);

    public function getProviderName();

    public function getHolderName($phoneNumber);
}
