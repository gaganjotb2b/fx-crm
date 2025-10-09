<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlinePaymentMethod extends Model
{
    protected $table = 'online_payment_methods';
    public $timestamps = true;
    use HasFactory;
    protected $fillable = [
        'name',
        'info',
        'status',
        'live_demo',
    ];
}
