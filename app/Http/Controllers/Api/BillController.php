<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BillController extends Controller
{
    public function generateQrCode(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $uuid = (string) Str::uuid();

        $bill = Bill::create([
            'uuid' => $uuid,
            'bill_data' => $validated['items'],
        ]);

        $url = route('bill.show', ['uuid' => $uuid]);

        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate($url);

        return response()->json([
            'success' => true,
            'uuid' => $uuid,
            'url' => $url,
            'qr_code' => base64_encode($qrCode),
        ]);
    }
}
