<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CommissionStatus extends Model
{
    protected $table = 'commission_status';
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'tradeable_type',
        'ticket',
        'login',
        'ib',
        'trader',
        'log',
        'status',
        'recount',
        'open_time',
        'close_time',
    ];

    public function trades(): MorphTo
    {
        return $this->morphTo('trades', 'tradeable_type', 'ticket', 'TICKET');
    }
    public function directIb(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ib', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trader', 'id');
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(TradingAccount::class, 'login', 'account_number');
    }
}
