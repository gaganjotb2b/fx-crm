<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M2Pay_Config extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $fillable = [
        'api_url',
        'api_token',
        'api_secret',
        'created_by',
        'ip_address',
    ];
}
