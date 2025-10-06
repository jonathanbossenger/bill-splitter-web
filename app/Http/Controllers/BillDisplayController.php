<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillDisplayController extends Controller
{
    public function show($uuid)
    {
        $bill = Bill::where('uuid', $uuid)->firstOrFail();
        
        return view('bill.show', [
            'bill' => $bill,
            'items' => $bill->bill_data,
        ]);
    }
}
