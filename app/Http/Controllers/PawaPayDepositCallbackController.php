<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesPawaPayDepositCallback;
use App\Services\Payments\VoteMobileMoneyService;
use Illuminate\Http\Request;

class PawaPayDepositCallbackController extends Controller
{
    use HandlesPawaPayDepositCallback;

    public function handle(Request $request, VoteMobileMoneyService $voteMobileMoneyService)
    {
        return $this->processDepositCallback($request, $voteMobileMoneyService);
    }
}
