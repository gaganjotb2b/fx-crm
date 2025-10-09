<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositSetting extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'min_amount',
        'max_amount',
        'created_by',
        'admin_log',
        'deposit_method',
    ];
}
