<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = ['uuid', 'bill_data'];

    protected $casts = [
        'bill_data' => 'array',
    ];
}
