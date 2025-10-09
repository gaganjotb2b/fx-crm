<?php

namespace App\Models;

ini_set('serialize_precision', 14);

use App\Models\admin\InternalTransfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Deposit extends Model
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
        return "The IP address $ip_address has been {$eventName} deposit";
    }
    protected $fillable = [
        'user_id',
        'invoice_id',
        'bank_account_id',
        'transaction_type',
        'invoice_id',
        'transaction_id',
        'amount', 'charge', 'approved_by', 'approved_status',
        'note',
        'approved_date',
        'account',
        'deposit_option',
        'internal_transfer',
        'charge',
        'approved_by',
        'bank_proof',
        'ip_address',
        'approved_status',
        'other_transaction_id',
        'bank_id',
        'currency',
        'local_currency',
        'admin_log',
        'client_log',
        'device_name',
        'wallet_type',
        'order_id',
        'created_by'
    ];
    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('deposit');
    }

    // Boot method to handle model events
    protected static function boot()
    {
        parent::boot();
        
        // When a deposit is created, update user's is_lead status
        static::created(function ($deposit) {
            // Update user's is_lead field to 1 (converted from lead to trader)
            \App\Models\User::where('id', $deposit->user_id)
                ->where('is_lead', 0) // Only update if currently a lead
                ->update(['is_lead' => 1]);
                
            // Log the change
            \Log::info('User converted from lead to trader due to deposit', [
                'user_id' => $deposit->user_id,
                'deposit_id' => $deposit->id,
                'deposit_amount' => $deposit->amount,
                'deposit_type' => $deposit->transaction_type
            ]);
        });
    }
    
    /**
     * Static method to convert all users with deposits from lead to trader
     * This can be used to update existing data
     */
    public static function convertLeadsWithDeposits()
    {
        // Get all user IDs who have at least one deposit
        $userIdsWithDeposits = self::distinct()->pluck('user_id');
        
        // Update their is_lead status to 1
        $updatedCount = \App\Models\User::whereIn('id', $userIdsWithDeposits)
            ->where('is_lead', 0) // Only update leads
            ->update(['is_lead' => 1]);
            
        \Log::info('Bulk conversion: Users with deposits converted from lead to trader', [
            'total_users_with_deposits' => $userIdsWithDeposits->count(),
            'users_updated' => $updatedCount
        ]);
        
        return $updatedCount;
    }

    public function otherTransaction()
    {
        return $this->belongsTo(OtherTransaction::class);
    }
    public function accountTransfer()
    {
        return $this->belongsTo(InternalTransfer::class, 'internal_transfer', 'id');
    }
    public function trader()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    // admin bank
    public function bank()
    {
        return $this->belongsTo(AdminBank::class, 'bank_id', 'id');
    }
}
