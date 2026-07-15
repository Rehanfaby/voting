<?php

namespace App\Http\Controllers\Concerns;

use App\Services\Payments\VoteMobileMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait HandlesPawaPayDepositCallback
{
    protected function processDepositCallback(Request $request, VoteMobileMoneyService $voteMobileMoneyService)
    {
        $payload = $request->all();

        Log::info('PawaPay deposit callback received', [
            'deposit_id' => $payload['depositId'] ?? null,
            'status' => $payload['status'] ?? null,
        ]);

        $result = $voteMobileMoneyService->handleProviderCallback('pawapay', $payload, $request->headers->all());

        if ($result && !empty($result['vote_id']) && ($result['status'] ?? null) === 'completed') {
            app(HomeController::class)->markVoteSuccessfulPublic(
                $result['vote_id'],
                $result['reference'] ?? 'pawapay_deposit_callback'
            );
        } elseif ($result && !empty($result['vote_id']) && ($result['status'] ?? null) === 'failed') {
            app(HomeController::class)->markVoteFailedPublic($result['vote_id']);
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
