<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLogin extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'facebook',
        'google',
        'mac',
        'admin_id'
    ];
}
