<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class H2pay extends Model
{
    public $timestamp = true;
    use HasFactory;

    protected $fillable = [
        'merchent_code',
        'user_name',
        'password',
        'security_code',
        'api_url',
        'created_by',
        'ip_address'
    ];
}
