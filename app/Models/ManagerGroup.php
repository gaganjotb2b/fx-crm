<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class ManagerGroup extends Model
{
    public $timestamps = true;
    use HasFactory, HasRoles, LogsActivity;
    // user activity log---------------------------------------------------
    // package use spatie
    // log the change attributes for all events
    protected static $recordEvents = ['created'];

    // Customize log description
    public function getDescriptionForEvent(string $eventName): string
    {
        $ip_address = request()->ip();
        return "The IP address $ip_address has been {$eventName} manager group";
    }
    protected $fillable = [
        'group_type',
        'group_name',
        'created_by'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('manager group');
    }
    // one to many relation ship
    public function manager_id()
    {
        return $this->hasMany(Manager::class, 'group_id')->select('user_id');
    }
    // one to one relation with manager model
    public function managers()
    {
        return $this->hasOne(Manager::class, 'group_id');
    }
}
