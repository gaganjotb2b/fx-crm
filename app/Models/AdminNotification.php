<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'admin_id',
        'notification_email',
        'nofitication_ruls',
        'admin_log',
        'status'
    ];
}
