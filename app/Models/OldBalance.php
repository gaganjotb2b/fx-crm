<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldBalance extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'user_id', 'balance',
    ];
}
