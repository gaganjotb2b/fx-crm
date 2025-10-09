<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoCurrency extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'symbol',
        'currency',
        'payment_currency',
        'created_by',
        'ip_address',
        'admin_log',
        'status',
    ];
}
