<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'master_password',
        'investor_password',
        'leverage',
        'admin_id'
    ];
}
