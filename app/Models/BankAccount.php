<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BankAccount extends Model
{

    public $timstamp = true;
    use HasFactory, LogsActivity;
    // user activity log---------------------------------------------------
    // package use spatie
    // log the change attributes for all events
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    // Customize log description
    public function getDescriptionForEvent(string $eventName): string
    {
        $ip_address = request()->ip();
        return "The IP address $ip_address has been {$eventName} admin";
    }
    /*
    |----------------------------------------------------------------------
    | one to many relation with Withdraw Model
    |----------------------------------------------------------------------
    */
    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_ac_name',
        'bank_ac_number',
        'bank_swift_code',
        'bank_iban',
        'bank_address',
        'bank_country',
        'currency_id',
        'note',
        'approve_status',
        'status',
        'admin_log',
        'client_log'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('Bank Account');
        // Chain fluent methods for configuration options
    }
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }
}
