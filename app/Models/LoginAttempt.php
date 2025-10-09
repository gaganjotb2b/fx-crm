<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'ip_address',
        'bad_login_attempt',
        'email',
        'date'
    ];
}
