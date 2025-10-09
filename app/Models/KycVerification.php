<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class KycVerification extends Model
{
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
        return "The IP address $ip_address has been {$eventName} kyc verification";
    }
    protected $fillable = [
        'user_id',
        'id_number',
        'issue_date',
        'exp_date',
        'doc_type',
        'perpose',
        'document_name',
        'status',
        'note',
        'approved_by',
        'approved_date',
        'admin_log'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])->useLogName('kyc verification');
    }
    public function documentType()
    {
        return $this->belongsTo(KycIdType::class, 'doc_type', 'id');
    }
}
