<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class OtherTransaction extends Model
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
        return "The IP address $ip_address has been {$eventName} other transaction";
    }
    protected $fillable = [
        'transaction_type',
        'crypto_type',
        'crypto_address',
        'crypto_amount',
        'account_name',
        'account_email',
        'crypto_instrument',
        'block_chain',
        'gateway_name',
        'ip_address',
        'status',
        'payment_id',
        'admin_log'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('other transaction');
    }

    /*
    |----------------------------------------------------------------------
    | one to one relation with Withdraw Model
    |----------------------------------------------------------------------
    */
    public function withdraw()
    {
        return $this->hasOne(Withdraw::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }
}
