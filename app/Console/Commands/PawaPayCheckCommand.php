<?php

namespace App\Console\Commands;

use App\Services\Payments\Gateways\PawaPayGateway;
use App\Services\Payments\MobileMoneyGatewayManager;
use Illuminate\Console\Command;

class PawaPayCheckCommand extends Command
{
    protected $signature = 'payments:pawapay-check';

    protected $description = 'Verify PawaPay configuration and Cameroon deposit availability';

    public function handle(MobileMoneyGatewayManager $manager)
    {
        if ($manager->activeProviderName() !== 'pawapay') {
            $this->warn('MOBILE_MONEY_PROVIDER is not set to pawapay.');
        }

        try {
            $manager->validateProvider('pawapay');
            $this->info('PawaPay provider configuration is valid.');
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $config = config('payments.mobile_money.providers.pawapay');
        $this->line('Environment: ' . ($config['environment'] ?? 'sandbox'));
        $this->line('Country: ' . ($config['country'] ?? 'CMR'));
        $this->line('Currency: ' . ($config['currency'] ?? 'XAF'));
        $this->line('API token: ' . (empty($config['api_token']) ? 'missing' : 'configured'));
        $this->line('API token ID: ' . (empty($config['api_token_id']) ? 'not set' : $config['api_token_id']));
        $this->line('Checkout callback: ' . ($config['callback_urls']['checkout'] ?: url('/api/payments/pawapay/checkouts/callback')));
        $this->line('Deposit callback: ' . ($config['callback_urls']['deposit'] ?: url('/api/payments/pawapay/deposits/callback')));
        $this->line('Payout callback: ' . ($config['callback_urls']['payout'] ?: url('/api/payments/pawapay/payouts/callback')));
        $this->line('Refund callback: ' . ($config['callback_urls']['refund'] ?: url('/api/payments/pawapay/refunds/callback')));

        /** @var PawaPayGateway $gateway */
        $gateway = $manager->driver('pawapay');
        $response = $gateway->getActiveConfiguration();
        $body = $response['body'] ?? [];

        if (($response['http_code'] ?? 0) >= 200 && ($response['http_code'] ?? 0) < 300) {
            $this->info('PawaPay active configuration endpoint responded successfully.');
            if (!empty($body)) {
                $this->line(json_encode($body));
            }
        } else {
            $this->warn('Could not fetch PawaPay active configuration. HTTP ' . ($response['http_code'] ?? 0));
        }

        return 0;
    }
}
