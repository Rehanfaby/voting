<?php

namespace App\Services\Payments;

use App\Http\Controllers\HomeController;
use App\User;
use App\vote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Settle Stripe Checkout vote payments server-side (webhook + return URL).
 * Recreates the vote row if it was deleted after payment succeeded.
 */
class StripeVoteSettlementService
{
    public function settleCheckoutSession($session): ?vote
    {
        $paymentStatus = (string) ($session->payment_status ?? '');
        if ($paymentStatus !== 'paid') {
            return null;
        }

        $paymentIntent = $session->payment_intent ?? null;
        if (is_object($paymentIntent)) {
            $paymentIntent = $paymentIntent->id ?? null;
        }
        $paymentIntent = $paymentIntent ? (string) $paymentIntent : null;

        if ($paymentIntent) {
            $already = vote::where('reference', $paymentIntent)->where('status', 1)->first();
            if ($already) {
                return $already;
            }
        }

        $meta = $this->metadataArray($session);
        $voteId = isset($meta['vote_id']) ? (int) $meta['vote_id'] : 0;
        $vote = $voteId > 0 ? vote::find($voteId) : null;

        if (!$vote) {
            $vote = $this->recreateVoteFromSession($session, $meta, $paymentIntent);
        }

        if (!$vote) {
            Log::error('Stripe vote settlement: unable to find or recreate vote', [
                'session' => $session->id ?? null,
                'vote_id' => $voteId,
                'payment_intent' => $paymentIntent,
            ]);
            return null;
        }

        if (Schema::hasColumn('votes', 'payment_provider') && empty($vote->payment_provider)) {
            $vote->payment_provider = 'stripe';
            $vote->save();
        }

        /** @var HomeController $home */
        $home = app(HomeController::class);
        $home->markVoteSuccessfulPublic($vote->id, $paymentIntent ?: ($vote->reference ?: $session->id));

        return vote::find($vote->id);
    }

    private function metadataArray($session): array
    {
        $meta = $session->metadata ?? null;
        if (is_object($meta) && method_exists($meta, 'toArray')) {
            return $meta->toArray();
        }
        if (is_array($meta)) {
            return $meta;
        }

        return [];
    }

    private function recreateVoteFromSession($session, array $meta, ?string $paymentIntent): ?vote
    {
        $userId = isset($meta['user_id']) ? (int) $meta['user_id'] : 0;
        $musicianId = isset($meta['musician_id']) ? (int) $meta['musician_id'] : 0;
        $voteCount = isset($meta['vote_count']) ? (int) $meta['vote_count'] : 0;
        $amount = isset($meta['amount']) ? (int) $meta['amount'] : (int) ($session->amount_total ?? 0);
        $whatsapp = $meta['whatsapp'] ?? null;
        $locale = $meta['locale'] ?? 'en';
        $price = isset($meta['price']) ? (int) $meta['price'] : 0;

        if ($userId <= 0 || $musicianId <= 0 || $voteCount <= 0) {
            return null;
        }

        if (!User::find($userId)) {
            return null;
        }

        if ($price <= 0 && $voteCount > 0) {
            $price = (int) round($amount / max(1, $voteCount));
        }

        $payload = [
            'user_id' => $userId,
            'musician_id' => $musicianId,
            'vote' => $voteCount,
            'status' => 0,
            'reference' => $paymentIntent ?: ('stripe-' . ($session->id ?? uniqid())),
            'price' => $price,
            'grand_total' => $amount,
            'whatsapp_number' => $whatsapp,
            'locale' => $locale,
        ];

        if (Schema::hasColumn('votes', 'payment_provider')) {
            $payload['payment_provider'] = 'stripe';
        }

        $vote = vote::create($payload);

        Log::warning('Stripe vote settlement recreated missing vote row', [
            'vote_id' => $vote->id,
            'session' => $session->id ?? null,
            'original_vote_id' => $meta['vote_id'] ?? null,
            'payment_intent' => $paymentIntent,
        ]);

        return $vote;
    }
}
