<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriggerFlug extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'admin_bank',
        'admin_bank_log',
        'client_bank',
        'deposit',
        'deposit_log',
        'withdraw',
        'withdraw_log',
        'admin',
        'admin_notification',
        'other_transaction',
    ];
}
