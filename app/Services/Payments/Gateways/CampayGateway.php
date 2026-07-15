<?php

namespace App\Services\Payments\Gateways;

use App\Helpers\PhoneHelper;
use App\Services\Payments\Contracts\MobileMoneyGatewayInterface;
use App\Services\Payments\Data\PaymentCallbackResult;
use App\Services\Payments\Data\PaymentInitiationResult;
use App\Services\Payments\Data\PaymentRequestData;
use App\Services\Payments\Data\PaymentStatusResult;
use App\Services\Payments\Data\RefundResult;
use App\Services\Payments\Enums\PaymentStatus;
use App\Services\Payments\Support\CameroonPhoneNormalizer;
use App\Services\Payments\Support\MobileMoneyPaymentLogger;

class CampayGateway implements MobileMoneyGatewayInterface
{
    public function getProviderName()
    {
        return 'campay';
    }

    public function initiateDeposit(PaymentRequestData $data)
    {
        $token = $this->token();
        if (!$token) {
            return new PaymentInitiationResult([
                'success' => false,
                'status' => PaymentStatus::FAILED,
                'message' => trans('file.Mobile Money payment is temporarily unavailable. Please try again later or contact support.'),
                'failureCode' => 'AUTHENTICATION_ERROR',
            ]);
        }

        $phone = CameroonPhoneNormalizer::normalize($data->phoneNumber);
        $payload = [
            'amount' => (string) (int) $data->amount,
            'from' => $phone,
            'description' => $data->description,
            'external_reference' => (string) $data->clientReferenceId,
            'currency' => $data->currency,
        ];

        $response = $this->request('POST', rtrim($this->baseUrl(), '/') . '/collect/', $payload, $token);
        $decoded = $response['body'];

        if (!empty($decoded['reference'])) {
            return new PaymentInitiationResult([
                'success' => true,
                'status' => PaymentStatus::PENDING,
                'providerStatus' => 'PENDING',
                'providerTransactionId' => $decoded['reference'],
                'providerReference' => $decoded['reference'],
                'message' => 'Payment request sent. Approve the transaction on your phone.',
                'rawResponse' => $decoded,
            ]);
        }

        $message = $this->failureMessage($response['http_code'], $decoded);

        MobileMoneyPaymentLogger::warning('Campay collect failed', [
            'provider' => 'campay',
            'http_code' => $response['http_code'],
            'phone_number' => $phone,
            'amount' => $data->amount,
            'response' => $decoded,
        ]);

        return new PaymentInitiationResult([
            'success' => false,
            'status' => PaymentStatus::FAILED,
            'providerStatus' => 'REJECTED',
            'message' => $message,
            'failureCode' => $decoded['code'] ?? 'COLLECT_FAILED',
            'failureMessage' => $decoded['message'] ?? null,
            'rawResponse' => $decoded,
        ]);
    }

    public function getTransactionStatus($providerTransactionId)
    {
        $token = $this->token();
        if (!$token) {
            return new PaymentStatusResult([
                'status' => PaymentStatus::PENDING,
                'providerStatus' => 'UNKNOWN',
            ]);
        }

        $response = $this->request(
            'GET',
            rtrim($this->baseUrl(), '/') . '/transaction/' . rawurlencode($providerTransactionId) . '/',
            null,
            $token
        );

        $providerStatus = strtoupper((string) ($response['body']['status'] ?? 'PENDING'));

        return new PaymentStatusResult([
            'status' => PaymentStatus::fromCampayStatus($providerStatus),
            'providerStatus' => $providerStatus,
            'providerReference' => $providerTransactionId,
            'rawResponse' => $response['body'],
        ]);
    }

    public function processCallback(array $payload, array $headers = [])
    {
        $result = new PaymentCallbackResult([
            'handled' => true,
            'providerTransactionId' => $payload['reference'] ?? null,
            'providerReference' => $payload['reference'] ?? null,
            'providerStatus' => strtoupper((string) ($payload['status'] ?? '')),
            'rawPayload' => $payload,
        ]);

        $result->status = PaymentStatus::fromCampayStatus($result->providerStatus);

        return $result;
    }

    public function refund($providerTransactionId, $amount = null)
    {
        return new RefundResult([
            'success' => false,
            'status' => PaymentStatus::FAILED,
            'message' => 'Campay refunds are not implemented for this provider.',
        ]);
    }

    public function getHolderName($phoneNumber)
    {
        $token = $this->token();
        if (!$token) {
            return null;
        }

        $phone = preg_replace('/\D/', '', (string) $phoneNumber);
        $response = $this->request(
            'GET',
            rtrim($this->baseUrl(), '/') . '/holder_info/?phone_number=' . $phone,
            null,
            $token
        );

        if (!empty($response['body']['full_name'])) {
            return trim($response['body']['full_name']);
        }

        return null;
    }

    public function validateWebhookSecret(array $headers, array $payload)
    {
        $secret = config('payments.mobile_money.providers.campay.webhook_secret')
            ?: config('app.campay_webhook_secret');

        if (!$secret) {
            return true;
        }

        $provided = $headers['X-Campay-Secret'][0] ?? $headers['x-campay-secret'][0] ?? ($payload['secret'] ?? null);

        return hash_equals((string) $secret, (string) $provided);
    }

    protected function token()
    {
        $token = config('payments.mobile_money.providers.campay.token');
        if ($token) {
            return $token;
        }

        return PhoneHelper::momoToken();
    }

    protected function baseUrl()
    {
        return config('payments.mobile_money.providers.campay.base_url', 'https://www.campay.net/api');
    }

    protected function request($method, $url, $payload = null, $token = null)
    {
        $curl = curl_init();
        $headers = ['Content-Type: application/json'];
        if ($token) {
            $headers[] = 'Authorization: Token ' . $token;
        }

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($payload !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'http_code' => $httpCode,
            'body' => json_decode((string) $response, true) ?: [],
        ];
    }

    protected function failureMessage($httpCode, array $decoded)
    {
        $msg = strtolower((string) ($decoded['message'] ?? ''));

        if (strpos($msg, 'inactive') !== false
            || strpos($msg, 'unauthorized') !== false
            || (int) $httpCode === 401
            || (int) $httpCode === 403) {
            return trans('file.Mobile Money payment is temporarily unavailable. Please try again later or contact support.');
        }

        return trans('file.We could not start the Mobile Money payment. Please check your number and try again.');
    }
}
