<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class WalletUpDown extends Model
{
    public $timestamps = true;
    use HasFactory, HasRoles, LogsActivity;
    // user activity log---------------------------------------------------
    // package use spatie
    // log the change attributes for all events
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    // Customize log description
    public function getDescriptionForEvent(string $eventName): string
    {
        $ip_address = request()->ip();
        return "The IP address $ip_address has been {$eventName} wallet up down";
    }
    protected $fillable = [
        'user_id',
        'txn_by',
        'txn_type',
        'amount',
        'incode',
        'note',
        'status',
        'admin_log',
        'approved_date',
        'wallet_type',
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('wallet up down');
    }
}
