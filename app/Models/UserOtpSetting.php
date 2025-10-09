<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtpSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'account_create',
        'deposit',
        'withdraw',
        'transfer',
        'user_id'
    ];
}
