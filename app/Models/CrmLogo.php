<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmLogo extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable=[
        'dark_layout',
        'light_layout',
        'email_logo',
        'fevicon',
        'ratio',
    ];
}
