<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileAppSetting extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'logo_loader',
        'logo_brand',
        'theme',
    ];
}
