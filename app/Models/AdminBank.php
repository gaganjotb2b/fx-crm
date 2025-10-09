<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class AdminBank extends Model
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
        return "The IP address $ip_address has been {$eventName} withdraw";
    }

    protected $fillable = [
        'tab_selection',
        'tab_name',
        'bank_name',
        'account_name',
        'account_number',
        'swift_code',
        'ifsc_code',
        'routing',
        'bank_country',
        'bank_address',
        'currency_id',
        'minimum_deposit',
        'note',
        'status',
        'admin_log'
    ];

    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('Admin bank');
    }
}
