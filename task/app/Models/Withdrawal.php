<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdrawal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'customer_name',
        'customer_phone',
        'customer_email',
        'manager_id',
        'country_id',
        'amount',
        'usdt_rate',
        'amount_in_usdt',
        'account_number',
        'description',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'usdt_rate' => 'decimal:2',
        'amount_in_usdt' => 'decimal:2'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 