<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempTransaction extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'transaction_id',
        'type',
        'properties',
        'log',
        'user_id',
    ];
}
