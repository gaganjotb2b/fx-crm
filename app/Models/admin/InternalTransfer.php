<?php

namespace App\Models\admin;

use App\Models\TradingAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class InternalTransfer extends Model
{
    protected $table = "internal_transfers";
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
        return "The IP address $ip_address has been {$eventName} internal transfer";
    }
    protected $fillable = [
        'user_id',
        'account_type',
        'profit_share_percentage',
        'platform',
        'account_id',
        'invoice_code',
        'amount',
        'charge',
        'type',
        'status',
        'note',
        'approved_by',
        'approved_date',
        'admin_log',
        'client_log',
        'order_id',
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('internal transfer');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function tradingAccount()
    {
        return $this->belongsTo(TradingAccount::class, 'account_id', 'id');
    }
    
    
    public function account()
    {
        return $this->belongsTo(TradingAccount::class, 'account_id', 'id');
    }
    public function bonusRequest()
    {
        return $this->hasMany(BonusRequest::class, 'account_id', 'account_id');
    }
    public function pammTrade()
    {
        return $this->belongsTo(PammTrade::class, 'pamm_trade', 'id');
    }
}
