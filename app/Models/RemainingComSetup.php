<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemainingComSetup extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'remaining',
        'first_level',
        'amount',
        'ip_address',
    ];
}
