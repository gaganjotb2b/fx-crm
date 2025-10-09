<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycRequired extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'deposit',
        'withdraw',
        'open_account',
        'system_deisable',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
