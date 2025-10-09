<?php

namespace App\Models;

use App\Services\api\FileApiService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class UserDescription extends Model
{
    protected $table = 'user_descriptions';
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
        return "The IP address $ip_address has been {$eventName} user description";
    }
    protected $fillable = [
        'user_id',
        'country_id',
        'city',
        'state',
        'address',
        'zip_code',
        'gender',
        'date_of_birth',
        'profile_avater'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('user description');
    }
    // relationship with country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    // profile photo
    public function getProfileAvaterAttribute($value)
    {
        // Add your prefix to the image URL
        if ($value) {
            $prefix = FileApiService::publi_url();
            return $prefix . $value;
        }
        return default_avater();
    }
    public function getProfileAvaterWithoutPrefix()
    {
        return $this->attributes['profile_avater'] ?? null;
    }
}
