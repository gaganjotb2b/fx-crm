<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PammUser extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamp = true;
    protected $fillable = [
        'user_id',
        'account',
        'name',
        'email',
        'username',
        'min_deposit',
        'max_deposit',
        'share_profit',
        'status',
        'admin_log',
        'request_status',
        'approved_date'
    ];
    public function tradingAccount(): BelongsTo
    {
        return $this->belongsTo(TradingAccount::class, 'account', 'account_number');
    }
    public function trades(): HasMany
    {
        return $this->hasMany(PammTrade::class, 'pamm_id', 'id');
    }
    public function investors(): HasMany
    {
        return $this->hasMany(PammInvestor::class, 'pamm_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
