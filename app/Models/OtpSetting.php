<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'account_create',
        'deposit',
        'withdraw',
        'transfer',
        'admin_id'
    ];
}
