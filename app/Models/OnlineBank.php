<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineBank extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'country',
        'currency',
        'bank_code',
        'bank_name',
        'status',
        'ip_address',
        'created_at',
        'created_by',
        'admin_log',
    ];
}
