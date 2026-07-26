<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsAppFormatter;
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
            /** @var HomeController $home */
            $home = app(HomeController::class);

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

            // Abandoned / expired Checkout or failed card attempt → notify voter.
            if (in_array($event->type, ['checkout.session.expired', 'checkout.session.async_payment_failed'], true)) {
                $session = $event->data->object;
                $voteId = $session->metadata->vote_id ?? null;
                if ($voteId) {
                    [$fr, $en] = $this->failureReasonsFromSession($session);
                    $home->markVoteFailedPublic($voteId, $fr, $en);
                    Log::info('Stripe webhook marked vote failed from session event', [
                        'type' => $event->type,
                        'vote_id' => $voteId,
                        'session' => $session->id ?? null,
                    ]);
                }
            }

            if ($event->type === 'payment_intent.payment_failed') {
                $pi = $event->data->object;
                $voteId = $pi->metadata->vote_id ?? null;
                // Checkout sessions put metadata on the Session, not always on the PI.
                if (!$voteId) {
                    // Best-effort: ignore if we cannot map — return-URL path still covers most cases.
                    return response('OK', 200);
                }
                $err = $pi->last_payment_error ?? null;
                [$fr, $en] = WhatsAppFormatter::cardFailureReasonPair(
                    $err->decline_code ?? $err->code ?? null,
                    $err->message ?? null
                );
                $home->markVoteFailedPublic($voteId, $fr, $en);
            }
        } catch (\Throwable $e) {
            Log::error('Stripe webhook handler failed: ' . $e->getMessage(), [
                'type' => $event->type ?? null,
            ]);
            return response('Handler error', 500);
        }

        return response('OK', 200);
    }

    private function failureReasonsFromSession($session): array
    {
        $piId = $session->payment_intent ?? null;
        if (is_object($piId)) {
            $piId = $piId->id ?? null;
        }

        if (!$piId) {
            return WhatsAppFormatter::cardFailureReasonPair('checkout_expired');
        }

        try {
            $pi = \Stripe\PaymentIntent::retrieve($piId);
            $err = $pi->last_payment_error ?? null;
            $code = $err->decline_code ?? $err->code ?? null;
            $message = $err->message ?? null;

            if (!$code && !$message) {
                $charges = \Stripe\Charge::all(['payment_intent' => $piId, 'limit' => 3]);
                foreach ($charges->data as $ch) {
                    if (!empty($ch->failure_code) || !empty($ch->failure_message)) {
                        $code = $ch->failure_code ?: $code;
                        $message = $ch->failure_message ?: $message;
                        $seller = $ch->outcome->seller_message ?? '';
                        if ($seller) {
                            $message = trim(($message ? $message . ' ' : '') . $seller);
                        }
                        break;
                    }
                }
            }

            if (!$code && !$message) {
                return WhatsAppFormatter::cardFailureReasonPair('checkout_expired');
            }

            return WhatsAppFormatter::cardFailureReasonPair($code, $message);
        } catch (\Throwable $e) {
            return WhatsAppFormatter::cardFailureReasonPair('checkout_expired');
        }
    }
}
