<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusPackage extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'pkg_name',
        'credit_type',
        'bonus_amount',
        'bonus_crrency',
        'max_lot',
        'max_withdraw',
        'max_deposit',
        'min_deposit',
        'max_bonus',
        'bonus_for',
        'active_status',
        'is_global',
        'created_by',
        'start_date',
        'end_date',
        'expire_at',
        'expire_after',
        'expire_type',
        'min_lot',
        'bonus_type',
        'bonus_on'
    ];
}
