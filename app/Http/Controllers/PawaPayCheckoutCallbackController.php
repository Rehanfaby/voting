<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PawaPayCheckoutCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('PawaPay checkout callback received', [
            'checkout_id' => $request->input('checkoutId'),
            'status' => $request->input('status'),
        ]);

        return response()->json(['status' => 'ok'], 200);
    }
}
