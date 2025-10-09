<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpSetup extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'mail_driver',
        'host',
        'port',
        'mail_user',
        'mail_password',
        'mail_encryption'
    ];
}
