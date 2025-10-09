<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestTradingHistory extends Model
{
    use HasFactory;

    protected $table = 'pro_contest_trading_history';

    protected $fillable = [
        'contest_id',
        'user_id',
        'account_number',
        'total_profit',
        'total_lot',
        'total_trades',
        'win_rate',
        'best_trade',
        'trading_data',
        'last_updated'
    ];

    protected $casts = [
        'trading_data' => 'array',
        'last_updated' => 'datetime',
        'total_profit' => 'decimal:2',
        'total_lot' => 'decimal:2',
        'win_rate' => 'decimal:2',
        'best_trade' => 'decimal:2'
    ];

    /**
     * Get the contest that owns the trading history
     */
    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Get the user that owns the trading history
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contest join record
     */
    public function contestJoin()
    {
        return $this->belongsTo(ContestJoin::class, 'contest_id', 'contest_id')
                    ->where('account_number', $this->account_number);
    }

    /**
     * Scope to get trading history for a specific contest and account
     */
    public function scopeForContestAndAccount($query, $contestId, $accountNumber)
    {
        return $query->where('contest_id', $contestId)
                     ->where('account_number', $accountNumber);
    }

    /**
     * Scope to get trading history for a specific contest
     */
    public function scopeForContest($query, $contestId)
    {
        return $query->where('contest_id', $contestId);
    }

    /**
     * Scope to get trading history for a specific account
     */
    public function scopeForAccount($query, $accountNumber)
    {
        return $query->where('account_number', $accountNumber);
    }
}
