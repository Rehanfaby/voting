<?php

namespace App\Services\Payments;

use App\Helpers\PhoneHelper;
use App\Services\Payments\Contracts\MobileMoneyGatewayInterface;
use App\Services\Payments\Gateways\CampayGateway;
use App\Services\Payments\Gateways\PawaPayGateway;

class MobileMoneyGatewayManager
{
    public function driver($provider = null)
    {
        $provider = strtolower((string) ($provider ?: config('payments.mobile_money.default', 'campay')));
        $this->validateProvider($provider);

        switch ($provider) {
            case 'campay':
                return new CampayGateway();
            case 'pawapay':
                return new PawaPayGateway();
            default:
                throw new MobileMoneyGatewayConfigurationException('Unsupported mobile money provider: ' . $provider);
        }
    }

    public function activeProviderName()
    {
        return strtolower((string) config('payments.mobile_money.default', 'campay'));
    }

    public function validateProvider($provider = null)
    {
        $provider = strtolower((string) ($provider ?: config('payments.mobile_money.default', 'campay')));
        $config = config('payments.mobile_money.providers.' . $provider);

        if (!$config) {
            throw new MobileMoneyGatewayConfigurationException('Unsupported mobile money provider: ' . $provider);
        }

        if (empty($config['enabled'])) {
            throw new MobileMoneyGatewayConfigurationException(
                'The selected mobile money provider is disabled: ' . $provider
            );
        }

        if ($provider === 'campay') {
            if (PhoneHelper::paymentSimulate()) {
                return true;
            }

            if (empty($config['token'])) {
                throw new MobileMoneyGatewayConfigurationException('Campay API credentials are missing.');
            }

            return true;
        }

        if ($provider === 'pawapay') {
            if (empty($config['api_token'])) {
                throw new MobileMoneyGatewayConfigurationException('PawaPay API token is missing.');
            }

            $environment = strtolower((string) ($config['environment'] ?? 'sandbox'));
            if (!in_array($environment, ['sandbox', 'production', 'live'], true)) {
                throw new MobileMoneyGatewayConfigurationException('PawaPay environment is invalid.');
            }

            if (($config['country'] ?? 'CMR') !== 'CMR') {
                throw new MobileMoneyGatewayConfigurationException('PawaPay country must be CMR for this integration.');
            }

            if (strtoupper((string) ($config['currency'] ?? 'XAF')) !== 'XAF') {
                throw new MobileMoneyGatewayConfigurationException('PawaPay currency must be XAF for Cameroon.');
            }

            return true;
        }

        throw new MobileMoneyGatewayConfigurationException('Unsupported mobile money provider: ' . $provider);
    }
}
