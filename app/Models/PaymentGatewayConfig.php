<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewayConfig extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'gateway_name',
        'merchent_code',
        'user_name',
        'password',
        'api_url',
        'api_token',
        'api_secret',
        'ip_address',
        'created_by',
        'admin_log',
    ];
}
