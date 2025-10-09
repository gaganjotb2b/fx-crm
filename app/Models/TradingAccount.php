<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class TradingAccount extends Model
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
        return "The IP address $ip_address has been {$eventName} trading account";
    }
    protected $fillable = [
        'user_id',
        'account_number',
        'account_status',
        'platform',
        'group_id',
        'leverage',
        'base_currency',
        'client_type',
        'phone_password',
        'master_password',
        'investor_password',
        'comment',
        'approve_status',
        'approved_by',
        'approve_date',
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('trading account');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function group() {
        return $this->belongsTo(ClientGroup::class,'group_id','id');
    }
    public function mt5Trades()
    {
        return $this->hasMany(Mt5Trade::class, 'LOGIN', 'account_number');
    }
    public function pammUser()
    {
        return $this->belongsTo(PammUser::class, 'account_number', 'account');
    }
}
