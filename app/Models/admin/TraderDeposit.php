<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class TraderDeposit extends Model
{
    protected $table="deposits";
    public $timestamps = true;
    use HasFactory, HasRoles,LogsActivity;
    // user activity log---------------------------------------------------
    // package use spatie
    // log the change attributes for all events
    protected static $recordEvents = ['created', 'updated','deleted'];

    // Customize log description
    public function getDescriptionForEvent(string $eventName): string
    {
        $ip_address = request()->ip();
        return "The IP address $ip_address has been {$eventName} deposit";
    }
    protected $fillable = [
        'user_id',
        'invoice_id',
        'account',
        'bank_account_id',
        'transaction_type',
        'amount',
        'charge_id',
        'approved_by',
        'order_id',
        'bank_proof',
        'ip_address',
        'approved_status',
        'note',
        'approved_date',
    ];
    // protected $guarded=[];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])->useLogName('Trader Deposit');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
