<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Manager extends Model
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
        return "The IP address $ip_address has been {$eventName} manager";
    }
    protected $fillable = [
        'user_id',
        'group_id',
        'priority',
        'is_mailable',
        'monthly_limit',
        'daily_limit'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('manager');
    }

    public function trader_under_manager()
    {
        return $this->hasMany(ManagerUser::class, 'manager_id', 'user_id');
    }
    // relation with manager group
    public function managerGroup()
    {
        return $this->belongsTo(ManagerGroup::class, 'group_id');
    }
}
