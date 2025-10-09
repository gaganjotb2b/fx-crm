<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusFor extends Model
{
    use HasFactory;
    protected $table = 'bonus_for';
    public $timestamp = true;
    protected $fillable = [
        'user_id',
        'bonus_package',
    ];
}
