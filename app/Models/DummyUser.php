<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DummyUser extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'group',
        'name',
        'email',
        'phone',
        'password',
        'email_verified_at',
        'transaction_pin',
        'sending_email',
        'auto_activate',
        'date_of_birth',
        'gender',
        'country',
        'state',
        'city',
        'zipcode',
        'address',
        'created_at',
        'updated_at',
        'ip_address',
        'sending_mail'
    ];
}
