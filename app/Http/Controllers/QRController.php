<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    // Show QR code
    public function show()
    {
        $token = Str::random(10);

        // Store token in DB (optional)
        DB::table('qr_tokens')->insert([
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $url = url("/scan/$token");

        // Generate QR code
        $qr = QrCode::size(300)->generate($url);

        return view('qr', compact('qr', 'token'));
    }

    // Handle scan
    public function scan($token)
    {
        $record = DB::table('qr_tokens')->where('token', $token)->first();

        if (!$record) {
            return "❌ Invalid or expired QR token.";
        }

        if ($record->used) {
            return "⚠️ Token already used.";
        }

        // Mark as used
        DB::table('qr_tokens')->where('token', $token)->update([
            'used' => true,
            'updated_at' => now()
        ]);

        // Perform action here
        return "✅ Token scanned successfully! Action performed.";
    }
}
