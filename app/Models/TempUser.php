<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempUser extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'email',
        'name',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'zipcode',
        'city',
        'state',
        'country',
        'skype',
        'linkedin',
        'facebook',
        'twitter',
        'telegram',
        'platform',
        'account_type',
        'leverage',
        'password'
    ];
}
