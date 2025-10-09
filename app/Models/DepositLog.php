<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositLog extends Model
{
    use HasFactory;
    protected $table = 'deposit_logs';
    public $timestamps = true;
    protected $fillable = [
        'properties',
    ];
}
