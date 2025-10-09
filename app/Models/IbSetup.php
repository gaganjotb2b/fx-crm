<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class IbSetup extends Model
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
        return "The IP address $ip_address has been {$eventName} IB Setup";
    }
    protected $fillable = [
        'ib_level',
        'colors',
        'require_sub_ib',
        'min_withdraw',
        'max_withdraw',
        'withdraw_period',
        'withdraw_kyc',
        'refer_kyc',
        'ib_commission_kyc',
        'period_days',
        'period_date'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('IB Setup');
    }
}
