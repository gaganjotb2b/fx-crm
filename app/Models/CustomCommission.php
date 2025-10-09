<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCommission extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'commission_id',
        'custom_commission',
    ];
}
