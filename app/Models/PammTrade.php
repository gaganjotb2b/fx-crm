<?php

namespace App\Models;

use App\Jobs\InvestorLosses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PammTrade extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamp = true;
    protected $fillable = [
        'pamm_id',
        'user_id',
        'platform',
        "sl",
        "tp",
        "cmd",
        'state',
        "login",
        "order",
        "state",
        "taxes",
        "digits",
        "profit",
        "reason",
        "symbol",
        "volume",
        "comment",
        "api_data",
        "open_time",
        "timestamp",
        "activation",
        "close_time",
        "commission",
        "open_price",
        "close_price",
        "commission_agent",
    ];
    public function pammUser(): BelongsTo
    {
        return $this->belongsTo(PammUser::class, 'pamm_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function InvestorLoss(): HasMany
    {
        return $this->hasMany(InvestorLossTrade::class, 'pamm_trade_id', 'id');
    }
    public function InvestorProfit(): HasMany
    {
        return $this->hasMany(InternalTransfer::class, 'pamm_trade', 'id');
    }
}
