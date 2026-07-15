<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesPawaPayDepositCallback;
use App\Services\Payments\VoteMobileMoneyService;
use Illuminate\Http\Request;

/**
 * Legacy alias for the deposit callback route.
 */
class PawaPayCallbackController extends Controller
{
    use HandlesPawaPayDepositCallback;

    public function handle(Request $request, VoteMobileMoneyService $voteMobileMoneyService)
    {
        return $this->processDepositCallback($request, $voteMobileMoneyService);
    }
}
