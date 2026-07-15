<?php

namespace App\Services\Payments\Enums;

class PaymentStatus
{
    const CREATED = 'created';
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const FAILED = 'failed';
    const CANCELLED = 'cancelled';
    const REFUNDED = 'refunded';
    const REFUND_PENDING = 'refund_pending';
    const REFUND_FAILED = 'refund_failed';
    const NOT_FOUND = 'not_found';

    public static function isFinal($status)
    {
        return in_array($status, [
            self::COMPLETED,
            self::FAILED,
            self::CANCELLED,
            self::REFUNDED,
            self::REFUND_FAILED,
        ], true);
    }

    public static function fromPawaPayStatus($providerStatus)
    {
        $status = strtoupper((string) $providerStatus);

        switch ($status) {
            case 'ACCEPTED':
                return self::PENDING;
            case 'SUBMITTED':
            case 'PROCESSING':
                return self::PROCESSING;
            case 'COMPLETED':
                return self::COMPLETED;
            case 'FAILED':
            case 'REJECTED':
                return self::FAILED;
            case 'NOT_FOUND':
                return self::NOT_FOUND;
            default:
                return self::PENDING;
        }
    }

    public static function fromCampayStatus($providerStatus)
    {
        $status = strtoupper((string) $providerStatus);

        switch ($status) {
            case 'SUCCESSFUL':
                return self::COMPLETED;
            case 'FAILED':
                return self::FAILED;
            case 'PENDING':
                return self::PENDING;
            default:
                return self::PROCESSING;
        }
    }
}
