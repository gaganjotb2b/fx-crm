<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSetup extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'theme_name',
        'theme_version',
        'use_for',
    ];
}
