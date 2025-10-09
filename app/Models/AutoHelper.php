<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoHelper extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'next',
        'relation'
    ];
}
