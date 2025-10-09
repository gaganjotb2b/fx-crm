<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfig extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'demo_api_key',
        'live_api_key',
        'server_ip',
        'manager_login',
        'server_port',
        'web_password',
        'manager_password',
        'api_key',
        'api_url',
        'platform_type',
        'server_type',
        'api_type',
        'status'
    ];
}
