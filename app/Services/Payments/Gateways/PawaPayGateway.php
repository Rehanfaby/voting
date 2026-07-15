<?php

namespace App\Services\Payments\Gateways;

use App\Services\Payments\Contracts\MobileMoneyGatewayInterface;
use App\Services\Payments\Data\PaymentCallbackResult;
use App\Services\Payments\Data\PaymentInitiationResult;
use App\Services\Payments\Data\PaymentRequestData;
use App\Services\Payments\Data\PaymentStatusResult;
use App\Services\Payments\Data\RefundResult;
use App\Services\Payments\Enums\PaymentStatus;
use App\Services\Payments\Support\CameroonNetworkMapper;
use App\Services\Payments\Support\CameroonPhoneNormalizer;
use App\Services\Payments\Support\MobileMoneyPaymentLogger;
use App\Services\Payments\Support\PawaPayFailureMessages;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class PawaPayGateway implements MobileMoneyGatewayInterface
{
    public function getProviderName()
    {
        return 'pawapay';
    }

    public function initiateDeposit(PaymentRequestData $data)
    {
        $provider = CameroonNetworkMapper::map($data->network);
        $phone = CameroonPhoneNormalizer::normalize($data->phoneNumber);
        $depositId = $data->providerTransactionId;

        $payload = [
            'depositId' => $depositId,
            'amount' => (string) (int) $data->amount,
            'currency' => $data->currency,
            'payer' => [
                'type' => 'MMO',
                'accountDetails' => [
                    'phoneNumber' => $phone,
                    'provider' => $provider,
                ],
            ],
            'clientReferenceId' => (string) $data->clientReferenceId,
            'customerMessage' => $this->customerMessage($data->description),
            'metadata' => [
                ['paymentId' => (string) $data->clientReferenceId],
                ['purpose' => 'vote'],
            ],
        ];

        $response = $this->request('POST', '/v2/deposits', $payload);
        $body = $response['body'];
        $providerStatus = strtoupper((string) ($body['status'] ?? ''));

        MobileMoneyPaymentLogger::info('PawaPay deposit initiated', [
            'provider' => 'pawapay',
            'deposit_id' => $depositId,
            'provider_status' => $providerStatus,
            'http_code' => $response['http_code'],
            'phone_number' => $phone,
            'amount' => $data->amount,
            'mobile_network' => $provider,
        ]);

        if ($providerStatus === 'ACCEPTED') {
            return new PaymentInitiationResult([
                'success' => true,
                'status' => PaymentStatus::PENDING,
                'providerStatus' => $providerStatus,
                'providerTransactionId' => $depositId,
                'providerReference' => $body['providerTransactionId'] ?? null,
                'message' => 'Payment request sent. Approve the transaction on your phone.',
                'rawResponse' => $body,
            ]);
        }

        if ($providerStatus === 'DUPLICATE_IGNORED') {
            return new PaymentInitiationResult([
                'success' => true,
                'status' => PaymentStatus::PENDING,
                'providerStatus' => $providerStatus,
                'providerTransactionId' => $depositId,
                'message' => 'Payment request sent. Approve the transaction on your phone.',
                'rawResponse' => $body,
            ]);
        }

        if ($providerStatus === 'REJECTED' || $response['http_code'] >= 400) {
            $failure = $body['failureReason'] ?? [];
            $failureCode = $failure['failureCode'] ?? ($body['failureCode'] ?? 'REJECTED');

            return new PaymentInitiationResult([
                'success' => false,
                'status' => PaymentStatus::FAILED,
                'providerStatus' => $providerStatus ?: 'REJECTED',
                'providerTransactionId' => $depositId,
                'message' => PawaPayFailureMessages::customerMessage($failureCode),
                'failureCode' => $failureCode,
                'failureMessage' => $failure['failureMessage'] ?? null,
                'rawResponse' => $body,
            ]);
        }

        if ($response['uncertain']) {
            return new PaymentInitiationResult([
                'success' => true,
                'status' => PaymentStatus::PENDING,
                'providerStatus' => 'UNCERTAIN',
                'providerTransactionId' => $depositId,
                'message' => 'Payment request sent. Approve the transaction on your phone.',
                'uncertain' => true,
                'rawResponse' => $body,
            ]);
        }

        return new PaymentInitiationResult([
            'success' => false,
            'status' => PaymentStatus::FAILED,
            'providerStatus' => $providerStatus,
            'providerTransactionId' => $depositId,
            'message' => PawaPayFailureMessages::customerMessage('UNKNOWN_ERROR'),
            'failureCode' => 'UNKNOWN_ERROR',
            'rawResponse' => $body,
        ]);
    }

    public function getTransactionStatus($providerTransactionId)
    {
        $response = $this->request('GET', '/v2/deposits/' . rawurlencode($providerTransactionId));
        $body = $response['body'];
        $providerStatus = strtoupper((string) ($body['status'] ?? 'NOT_FOUND'));
        $failure = $body['failureReason'] ?? [];

        return new PaymentStatusResult([
            'status' => PaymentStatus::fromPawaPayStatus($providerStatus),
            'providerStatus' => $providerStatus,
            'providerReference' => $body['providerTransactionId'] ?? null,
            'failureCode' => $failure['failureCode'] ?? null,
            'failureMessage' => $failure['failureMessage'] ?? null,
            'rawResponse' => $body,
        ]);
    }

    public function processCallback(array $payload, array $headers = [])
    {
        $providerStatus = strtoupper((string) ($payload['status'] ?? ''));
        $failure = $payload['failureReason'] ?? [];

        return new PaymentCallbackResult([
            'handled' => true,
            'providerTransactionId' => $payload['depositId'] ?? null,
            'providerReference' => $payload['providerTransactionId'] ?? null,
            'providerStatus' => $providerStatus,
            'status' => PaymentStatus::fromPawaPayStatus($providerStatus),
            'amount' => isset($payload['amount']) ? (int) $payload['amount'] : null,
            'currency' => $payload['currency'] ?? null,
            'mobileNetwork' => $payload['payer']['accountDetails']['provider'] ?? null,
            'failureCode' => $failure['failureCode'] ?? null,
            'failureMessage' => $failure['failureMessage'] ?? null,
            'rawPayload' => $payload,
        ]);
    }

    public function refund($providerTransactionId, $amount = null)
    {
        return new RefundResult([
            'success' => false,
            'status' => PaymentStatus::FAILED,
            'message' => 'PawaPay refunds are not enabled in this release.',
        ]);
    }

    public function getHolderName($phoneNumber)
    {
        return null;
    }

    public function getActiveConfiguration()
    {
        return $this->request('GET', '/v2/active-conf');
    }

    protected function request($method, $path, array $payload = null)
    {
        $client = new Client([
            'base_uri' => rtrim($this->baseUrl(), '/') . '/',
            'timeout' => $this->timeout(),
            'connect_timeout' => $this->connectTimeout(),
            'verify' => true,
            'http_errors' => false,
        ]);

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken(),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ];

        if ($payload !== null) {
            $options['json'] = $payload;
        }

        try {
            $response = $client->request($method, ltrim($path, '/'), $options);
            $body = json_decode((string) $response->getBody(), true) ?: [];

            return [
                'http_code' => $response->getStatusCode(),
                'body' => $body,
                'uncertain' => false,
            ];
        } catch (ConnectException $e) {
            MobileMoneyPaymentLogger::warning('PawaPay connection error', [
                'provider' => 'pawapay',
                'path' => $path,
                'message' => $e->getMessage(),
            ]);

            return [
                'http_code' => 0,
                'body' => [],
                'uncertain' => true,
            ];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $body = $response ? json_decode((string) $response->getBody(), true) : [];

            return [
                'http_code' => $response ? $response->getStatusCode() : 0,
                'body' => is_array($body) ? $body : [],
                'uncertain' => $response && $response->getStatusCode() >= 500,
            ];
        }
    }

    protected function baseUrl()
    {
        $environment = strtolower((string) config('payments.mobile_money.providers.pawapay.environment', 'sandbox'));

        if ($environment === 'production' || $environment === 'live') {
            return config('payments.mobile_money.providers.pawapay.live_base_url', 'https://api.pawapay.io');
        }

        return config('payments.mobile_money.providers.pawapay.sandbox_base_url', 'https://api.sandbox.pawapay.io');
    }

    protected function apiToken()
    {
        return (string) config('payments.mobile_money.providers.pawapay.api_token');
    }

    protected function timeout()
    {
        return (int) config('payments.mobile_money.providers.pawapay.timeout', 30);
    }

    protected function connectTimeout()
    {
        return (int) config('payments.mobile_money.providers.pawapay.connect_timeout', 10);
    }

    protected function customerMessage($description)
    {
        $message = preg_replace('/[^A-Za-z0-9 .\-]/', '', (string) $description);
        $message = trim($message) ?: 'Mulema Payment';

        return substr($message, 0, 22);
    }
}
