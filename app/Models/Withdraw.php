<?php

namespace App\Models;

use App\Models\admin\InternalTransfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Withdraw extends Model
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
        'user_id',
        'transaction_id',
        'transaction_type',
        'bank_account_id',
        'amount',
        'charge',
        'charge_id',
        'approved_status',
        'approved_by',
        'approved_date',
        'other_transaction_id',
        'currency',
        'local_currency',
        'admin_log',
        'wallet_type',
        'trading_account',
        'withdraw_option',
        'internal_transfer',
        'client_log',
        'created_by'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('withdraw');
    }


    /*
    |----------------------------------------------------------------------
    | one to many relation with User Model
    |----------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |----------------------------------------------------------------------
    | one to many relation with BankAccount Model
    |----------------------------------------------------------------------
    */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /*
    |----------------------------------------------------------------------
    | one to one relation with OtherTransaction Model
    |----------------------------------------------------------------------
    */
    public function otherTransaction()
    {
        return $this->belongsTo(OtherTransaction::class);
    }
    public function tradingAccount()
    {
        return $this->belongsTo(TradingAccount::class, 'trading_account', 'id');
    }
    public function accountTransfer()
    {
        return $this->belongsTo(InternalTransfer::class, 'internal_transfer', 'id');
    }
    public function trader()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    // client bank
    public function bank()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
}
