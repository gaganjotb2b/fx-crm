<?php

namespace App\Models;

use App\Services\CombinedService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class IB extends Model
{
    protected $table = 'ib';
    public $timestamps = true;
    // protected $primaryKey = 'ib_id';
    use HasFactory, HasRoles, LogsActivity;
    // user activity log---------------------------------------------------
    // package use spatie
    // log the change attributes for all events
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    // Customize log description
    public function getDescriptionForEvent(string $eventName): string
    {
        $ip_address = request()->ip();
        return "The IP address $ip_address has been {$eventName} IB";
    }
    protected $fillable = [
        'ib_id',
        'reference_id'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('IB');
    }

    public function childs()
    {
        $ch =  $this->hasMany('App\Models\IB', 'ib_id', 'reference_id')
            ->join('users', 'ib.ib_id', '=', 'users.id')
            ->where('users.type', CombinedService::type());
        if (CombinedService::is_combined()) {
            $ch = $ch->where('users.combine_access', "1");
        }

        return $ch;
    }
    // public function parents()
    // {
    //     return $this->hasMany('App\Models\IB', 'reference_id', 'ib_id')
    //         ->join('users', 'ib.ib_id', '=', 'users.id')
    //         ->where('users.type', 4);
    // }

    public function trader_childs()
    {
        $ch =  $this->hasMany('App\Models\IB', 'ib_id')
            ->where('users.type', 0)
            ->join('users', 'ib.ib_id', '=', 'users.id');
        if (CombinedService::is_combined()) {
            $ch = $ch->where('users.combine_access', 0);
        }
        return $ch;
    }
    public function traders()
    {
        return $this->hasMany(IB::class, 'ib_id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'reference_id');
    }
    public function child_ib()
    {
        return $this->hasMany(IB::class, 'ib_id');
    }
    public function referencesUser()
    {
        return $this->hasMany(IB::class, 'ib_id', 'reference_id')->with('referencesUser', 'referencesUser.referenceDetails', 'referencesUser.referenceDetails.tradingAccount');
    }
    public function ibReference()
    {
        return $this->hasMany(IB::class, 'ib_id', 'reference_id')->with('ibReference', 'ibReference.referenceDetails');
    }
    public function parents()
    {
        return $this->belongsTo(IB::class, 'ib_id', 'reference_id')->with('parents');
    }
    public function ibDetails()
    {
        return $this->belongsTo(User::class, 'ib_id', 'id');
    }
    public function referenceDetails(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reference_id', 'id');
    }
}
