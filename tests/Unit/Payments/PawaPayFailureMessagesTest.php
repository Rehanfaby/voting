<?php

namespace Tests\Unit\Payments;

use App\Services\Payments\Enums\PaymentStatus;
use App\Services\Payments\Support\PawaPayFailureMessages;
use Tests\TestCase;

class PawaPayFailureMessagesTest extends TestCase
{
    public function test_failure_codes_map_to_safe_messages()
    {
        $this->assertStringContainsString(
            'not approved',
            strtolower(PawaPayFailureMessages::customerMessage('PAYMENT_NOT_APPROVED'))
        );
        $this->assertStringContainsString(
            'insufficient balance',
            strtolower(PawaPayFailureMessages::customerMessage('INSUFFICIENT_BALANCE'))
        );
        $this->assertStringContainsString(
            'temporarily unavailable',
            strtolower(PawaPayFailureMessages::customerMessage('AUTHENTICATION_ERROR'))
        );
    }

    public function test_pawapay_status_mapping()
    {
        $this->assertSame(PaymentStatus::PENDING, PaymentStatus::fromPawaPayStatus('ACCEPTED'));
        $this->assertSame(PaymentStatus::COMPLETED, PaymentStatus::fromPawaPayStatus('COMPLETED'));
        $this->assertSame(PaymentStatus::FAILED, PaymentStatus::fromPawaPayStatus('FAILED'));
    }
}
