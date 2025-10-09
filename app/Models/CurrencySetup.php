<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencySetup extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'id',
        'currency',
        'currency_rate',
        'created_by',
        'transaction_type',
        'created_at',
        'updated_at',
        'ip',
    ];
}
