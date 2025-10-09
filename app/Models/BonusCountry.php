<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCountry extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'bonus_package',
        'country',
    ];
}
