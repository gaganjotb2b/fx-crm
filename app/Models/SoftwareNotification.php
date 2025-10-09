<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareNotification extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'type',
        'message',
        'user_type',
    ];
}
