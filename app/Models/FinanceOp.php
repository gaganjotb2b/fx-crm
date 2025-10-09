<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceOp extends Model
{
    protected $table = "finance_ops";
    public $timestamps = true;
    use HasFactory;
    protected $fillable = [
        'user_id',
        'deposit_operation',
        'withdraw_operation',
        'internal_transfer',
        'wta_transfer',
        'trader_to_trader',
        'trader_to_ib',
        'ib_to_ib',
        'ib_to_trader',
        'kyc_verify'
    ];
}
