<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareSetting extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'email_template',
        'account_move',
        'direct_deposit',
        'direct_withdraw',
        'is_multicurrency',
        'crypto_deposit',
        'admin_log'
    ];
}
