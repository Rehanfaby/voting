<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PawaPayRefundCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('PawaPay refund callback received', [
            'refund_id' => $request->input('refundId'),
            'deposit_id' => $request->input('depositId'),
            'status' => $request->input('status'),
        ]);

        return response()->json(['status' => 'ok'], 200);
    }
}
