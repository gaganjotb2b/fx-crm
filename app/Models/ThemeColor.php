<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeColor extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'primary_color',
        'secondary_color',
        'body_color',
        'form_color',
        'use_for',
    ];
}
