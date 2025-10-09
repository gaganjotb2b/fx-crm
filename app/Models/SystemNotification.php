<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'notification_type',
        'user_id',
        'user_type',
        'admin_id',
        'category',
        'notification',
        'status',
        'ip_address',
        'admin_log',
        'location_url',
        'table_id'
    ];
}
