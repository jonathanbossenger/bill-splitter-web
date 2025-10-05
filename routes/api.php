<?php

use App\Http\Controllers\Api\BillController;
use Illuminate\Support\Facades\Route;

Route::post('/bills/generate-qr', [BillController::class, 'generateQrCode']);
