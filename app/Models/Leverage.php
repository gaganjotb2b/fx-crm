<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leverage extends Model
{
    use HasFactory;
    public $timesptamp = true;
    protected $fillable = [
        'leverage'
    ];
}
