<?php

use App\Http\Controllers\BillDisplayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bill/{uuid}', [BillDisplayController::class, 'show'])->name('bill.show');
