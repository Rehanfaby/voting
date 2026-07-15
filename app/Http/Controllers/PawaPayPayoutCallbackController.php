<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PawaPayPayoutCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('PawaPay payout callback received', [
            'payout_id' => $request->input('payoutId'),
            'status' => $request->input('status'),
        ]);

        return response()->json(['status' => 'ok'], 200);
    }
}
