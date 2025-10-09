<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'name',
        'account_id',
        'address',
        'country',
        'city',
        'email',
        'comment',
        'group',
        'state',
        'leverage',
        'zipcode',
        'mqid',
        'password_phone',
        'id_number',
        'status',
        'taxes',
        'agent_account',
        'phone',
        'password',
        'password_investor',
        'enable_change_password',
        'enable',
        'send_reports',
        'enable_read_only',
        'account_type',
        'balance',
        'mergin',
    ];
}
