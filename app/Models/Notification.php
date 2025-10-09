<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'type',
        'description',
        'email',
        'status',
        'user_type',
        'notification_body',
        'notification_header',
        'notification_footer',
    ];
}
