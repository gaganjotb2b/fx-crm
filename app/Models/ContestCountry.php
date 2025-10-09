<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestCountry extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'country_id',
        'contest_id'
    ];
}
