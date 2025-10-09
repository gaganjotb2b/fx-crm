<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawSetting extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'min_amount',
        'max_amount',
        'created_by',
        'admin_log',
        'withdraw_method',
    ];
}
