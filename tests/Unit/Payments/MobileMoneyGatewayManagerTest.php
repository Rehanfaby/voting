<?php

namespace Tests\Unit\Payments;

use App\Services\Payments\MobileMoneyGatewayConfigurationException;
use App\Services\Payments\MobileMoneyGatewayManager;
use Tests\TestCase;

class MobileMoneyGatewayManagerTest extends TestCase
{
    public function test_pawapay_is_selected_from_configuration()
    {
        config([
            'payments.mobile_money.default' => 'pawapay',
            'payments.mobile_money.providers.pawapay.enabled' => true,
            'payments.mobile_money.providers.pawapay.api_token' => 'sandbox-token',
            'payments.mobile_money.providers.pawapay.environment' => 'sandbox',
            'payments.mobile_money.providers.pawapay.country' => 'CMR',
            'payments.mobile_money.providers.pawapay.currency' => 'XAF',
        ]);

        $manager = new MobileMoneyGatewayManager();
        $this->assertSame('pawapay', $manager->driver()->getProviderName());
    }

    public function test_campay_is_selected_from_configuration()
    {
        config([
            'payments.mobile_money.default' => 'campay',
            'payments.mobile_money.providers.campay.enabled' => true,
            'payments.mobile_money.providers.campay.token' => 'campay-token',
        ]);

        $manager = new MobileMoneyGatewayManager();
        $this->assertSame('campay', $manager->driver()->getProviderName());
    }

    public function test_missing_pawapay_token_throws_configuration_exception()
    {
        config([
            'payments.mobile_money.default' => 'pawapay',
            'payments.mobile_money.providers.pawapay.enabled' => true,
            'payments.mobile_money.providers.pawapay.api_token' => '',
        ]);

        $this->expectException(MobileMoneyGatewayConfigurationException::class);
        (new MobileMoneyGatewayManager())->validateProvider('pawapay');
    }
}
