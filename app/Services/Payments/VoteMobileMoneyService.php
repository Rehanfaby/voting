<?php

namespace App\Services\Payments;

use App\MobileMoneyPayment;
use App\Services\Payments\Data\PaymentRequestData;
use App\Services\Payments\Enums\PaymentStatus;
use App\Services\Payments\Support\CameroonNetworkMapper;
use App\Services\Payments\Support\CameroonPhoneNormalizer;
use App\Services\Payments\Support\MobileMoneyPaymentLogger;
use App\vote;
use Illuminate\Support\Str;

class VoteMobileMoneyService
{
    protected $manager;
    protected $statusProcessor;

    public function __construct(
        MobileMoneyGatewayManager $manager,
        PaymentStatusProcessor $statusProcessor
    ) {
        $this->manager = $manager;
        $this->statusProcessor = $statusProcessor;
    }

    public function initiate(vote $vote, $phone, $paymentMethod = 'momo')
    {
        $gateway = $this->manager->driver();
        $provider = $gateway->getProviderName();
        $amount = (int) $vote->grand_total;

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be greater than zero.');
        }

        $depositId = $provider === 'pawapay' ? (string) Str::uuid() : null;
        $publicReference = 'MUL-VOTE-' . $vote->id;

        $payment = MobileMoneyPayment::create([
            'public_reference' => $publicReference,
            'user_id' => $vote->user_id,
            'payable_type' => vote::class,
            'payable_id' => $vote->id,
            'provider' => $provider,
            'provider_transaction_id' => $depositId,
            'idempotency_key' => $depositId,
            'phone_number' => CameroonPhoneNormalizer::normalize($phone),
            'mobile_network' => CameroonNetworkMapper::fromPaymentMethod($paymentMethod),
            'amount' => $amount,
            'currency' => 'XAF',
            'status' => PaymentStatus::CREATED,
            'request_payload' => json_encode([
                'vote_id' => $vote->id,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
            ]),
        ]);

        $request = new PaymentRequestData([
            'payableType' => vote::class,
            'payableId' => $vote->id,
            'amount' => $amount,
            'currency' => 'XAF',
            'phoneNumber' => $phone,
            'network' => CameroonNetworkMapper::fromPaymentMethod($paymentMethod),
            'description' => 'Mulema Gospel Vote',
            'clientReferenceId' => (string) $vote->id,
            'userId' => $vote->user_id,
            'providerTransactionId' => $depositId,
            'publicReference' => $publicReference,
        ]);

        $result = $gateway->initiateDeposit($request);

        $payment->provider_transaction_id = $result->providerTransactionId ?: $payment->provider_transaction_id;
        $payment->provider_reference = $result->providerReference;
        $payment->provider_status = $result->providerStatus;
        $payment->status = $result->status;
        $payment->failure_code = $result->failureCode;
        $payment->failure_message = $result->failureMessage;
        $payment->initial_response_payload = json_encode($result->rawResponse);
        if ($result->status === PaymentStatus::FAILED) {
            $payment->failed_at = now();
        }
        $payment->save();

        $vote->payment_provider = $provider;
        $vote->mobile_money_payment_id = $payment->id;
        $vote->reference = $result->providerTransactionId ?: 'pending';
        $vote->save();

        MobileMoneyPaymentLogger::info('Vote mobile money payment initiated', [
            'provider' => $provider,
            'payment_id' => $payment->id,
            'public_reference' => $payment->public_reference,
            'deposit_id' => $payment->provider_transaction_id,
            'vote_id' => $vote->id,
            'amount' => $amount,
            'status' => $payment->status,
        ]);

        return [
            'payment' => $payment,
            'result' => $result,
        ];
    }

    /**
     * Start a new MoMo prompt for a still-pending vote (e.g. after ~4 minutes).
     * Keeps the same vote/voter row; creates a fresh provider transaction.
     */
    public function reinitiate(vote $vote, $minSecondsSinceLast = 240)
    {
        if ((int) $vote->status === 1) {
            return [
                'ok' => false,
                'code' => 'already_paid',
                'message' => 'This vote is already paid.',
            ];
        }
        if ((int) $vote->status === 2) {
            return [
                'ok' => false,
                'code' => 'failed',
                'message' => 'This vote can no longer be retried. Please start a new vote.',
            ];
        }

        $payment = $this->resolvePaymentForVote($vote);
        $lastAt = $payment && $payment->created_at
            ? $payment->created_at
            : $vote->created_at;
        $elapsed = $lastAt ? now()->diffInSeconds($lastAt) : 0;

        if ($elapsed < (int) $minSecondsSinceLast) {
            return [
                'ok' => false,
                'code' => 'too_soon',
                'wait_seconds' => (int) $minSecondsSinceLast - $elapsed,
                'message' => 'Please wait a few more minutes before retrying.',
            ];
        }

        $phone = $payment
            ? $payment->phone_number
            : optional($vote->voters)->phone;
        if (!$phone) {
            return [
                'ok' => false,
                'code' => 'missing_phone',
                'message' => 'No mobile money number found for this vote.',
            ];
        }

        $paymentMethod = 'momo';
        if ($payment && $payment->request_payload) {
            $payload = json_decode($payment->request_payload, true);
            if (!empty($payload['payment_method'])) {
                $paymentMethod = $payload['payment_method'];
            }
        } elseif ($payment && $payment->mobile_network) {
            $network = strtoupper((string) $payment->mobile_network);
            if (strpos($network, 'ORANGE') !== false) {
                $paymentMethod = 'om';
            }
        }

        $initiation = $this->initiate($vote, $phone, $paymentMethod);
        if (!empty($initiation['result']) && !$initiation['result']->success) {
            return [
                'ok' => false,
                'code' => 'initiate_failed',
                'message' => $initiation['result']->message
                    ?: 'We could not restart the Mobile Money payment.',
                'result' => $initiation['result'],
            ];
        }

        return [
            'ok' => true,
            'code' => 'restarted',
            'message' => 'A new payment prompt has been sent to your phone.',
            'payment' => $initiation['payment'] ?? null,
            'result' => $initiation['result'] ?? null,
            'payment_method' => $paymentMethod,
            'phone' => $phone,
        ];
    }

    public function refreshVoteStatus(vote $vote)
    {
        if ((int) $vote->status === 1) {
            return 'SUCCESSFUL';
        }
        if ((int) $vote->status === 2) {
            return 'FAILED';
        }

        $payment = $this->resolvePaymentForVote($vote);

        if ($payment && $payment->provider_transaction_id && $payment->provider_transaction_id !== 'pending') {
            $gateway = $this->manager->driver($payment->provider);
            $statusResult = $gateway->getTransactionStatus($payment->provider_transaction_id);
            $payment = $this->statusProcessor->applyStatusResult($payment, $statusResult);

            if ($payment && $payment->status === PaymentStatus::COMPLETED) {
                return 'SUCCESSFUL';
            }
            if ($payment && $payment->status === PaymentStatus::FAILED) {
                return 'FAILED';
            }

            return 'PENDING';
        }

        return $this->refreshLegacyCampayVoteStatus($vote);
    }

    public function handleProviderCallback($provider, array $payload, array $headers = [])
    {
        $gateway = $this->manager->driver($provider);
        $callback = $gateway->processCallback($payload, $headers);

        if ($provider === 'campay') {
            $externalRef = $payload['external_reference'] ?? null;
            if (!$externalRef) {
                return null;
            }

            return [
                'vote_id' => $externalRef,
                'status' => $callback->status,
                'reference' => $callback->providerReference,
            ];
        }

        if (!$callback->providerTransactionId) {
            return null;
        }

        $payment = MobileMoneyPayment::where('provider_transaction_id', $callback->providerTransactionId)->first();
        if (!$payment) {
            MobileMoneyPaymentLogger::warning('Callback received for unknown provider transaction', [
                'provider' => $provider,
                'provider_transaction_id' => $callback->providerTransactionId,
            ]);

            return null;
        }

        $payment = $this->statusProcessor->applyCallback($payment, $callback);

        return [
            'vote_id' => $payment ? $payment->payable_id : null,
            'status' => $payment ? $payment->status : null,
            'reference' => $payment ? ($payment->provider_reference ?: $payment->provider_transaction_id) : null,
        ];
    }

    public function reconcilePendingPayments($days = 3)
    {
        $since = now()->subDays((int) $days);
        $pending = MobileMoneyPayment::whereIn('status', [PaymentStatus::PENDING, PaymentStatus::PROCESSING])
            ->where('created_at', '>=', $since)
            ->whereNotNull('provider_transaction_id')
            ->get();

        $checked = 0;
        $confirmed = 0;
        $failed = 0;

        foreach ($pending as $payment) {
            try {
                $gateway = $this->manager->driver($payment->provider);
                $statusResult = $gateway->getTransactionStatus($payment->provider_transaction_id);
                $payment = $this->statusProcessor->applyStatusResult($payment, $statusResult);
                $checked++;

                if ($payment && $payment->status === PaymentStatus::COMPLETED) {
                    $confirmed++;
                } elseif ($payment && $payment->status === PaymentStatus::FAILED) {
                    $failed++;
                }
            } catch (\Throwable $e) {
                MobileMoneyPaymentLogger::warning('Payment reconciliation failed', [
                    'payment_id' => $payment->id,
                    'provider' => $payment->provider,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return compact('checked', 'confirmed', 'failed');
    }

    public function getHolderName($phone)
    {
        try {
            $gateway = $this->manager->driver();
            return $gateway->getHolderName($phone);
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function refreshLegacyCampayVoteStatus(vote $vote)
    {
        if (!$vote->reference || $vote->reference === 'pending') {
            return 'PENDING';
        }

        try {
            $gateway = $this->manager->driver('campay');
            $statusResult = $gateway->getTransactionStatus($vote->reference);

            if ($statusResult->status === PaymentStatus::COMPLETED) {
                return 'SUCCESSFUL';
            }
            if ($statusResult->status === PaymentStatus::FAILED) {
                return 'FAILED';
            }
        } catch (\Throwable $e) {
            MobileMoneyPaymentLogger::warning('Legacy vote status refresh failed', [
                'vote_id' => $vote->id,
                'message' => $e->getMessage(),
            ]);
        }

        return 'PENDING';
    }

    protected function resolvePaymentForVote(vote $vote)
    {
        if ($vote->mobile_money_payment_id) {
            return MobileMoneyPayment::find($vote->mobile_money_payment_id);
        }

        return MobileMoneyPayment::where('payable_type', vote::class)
            ->where('payable_id', $vote->id)
            ->orderByDesc('id')
            ->first();
    }
}
