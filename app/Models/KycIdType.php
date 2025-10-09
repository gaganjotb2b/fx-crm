<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class KycIdType extends Model
{
    protected $table = 'kyc_id_type';
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
        return "The IP address $ip_address has been {$eventName} kyc id type";
    }
    protected $fillable = [
        'id_type',
        'group',
        'created_by',
        'has_issue_date'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])->useLogName('kyc id type');
    }
}
