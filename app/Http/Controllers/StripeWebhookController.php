<?php

namespace App\Http\Controllers;

use App\Services\Payments\StripeVoteSettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, StripeVoteSettlementService $settlement)
    {
        $secret = config('services.stripe.webhook_secret') ?: env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        if (!$secret) {
            Log::error('Stripe webhook: STRIPE_WEBHOOK_SECRET is not configured');
            return response('Webhook secret not configured', 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook: invalid payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook: invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        Stripe::setApiKey(config('services.stripe.secret') ?: env('STRIPE_SECRET'));

        try {
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                // Only vote checkouts carry vote_id metadata.
                if (!empty($session->metadata->vote_id) || !empty($session->metadata->musician_id)) {
                    $vote = $settlement->settleCheckoutSession($session);
                    Log::info('Stripe webhook settled vote checkout', [
                        'session' => $session->id ?? null,
                        'vote_id' => optional($vote)->id,
                        'status' => optional($vote)->status,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Stripe webhook handler failed: ' . $e->getMessage(), [
                'type' => $event->type ?? null,
            ]);
            return response('Handler error', 500);
        }

        return response('OK', 200);
    }
}
