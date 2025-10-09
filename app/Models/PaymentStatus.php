<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $table = 'payment_status';
    public $timestamps = true;
    use HasFactory;
    protected $fillable = [
        'payment_id',
        'txid',
        'amount',
        'status',
    ];
}
