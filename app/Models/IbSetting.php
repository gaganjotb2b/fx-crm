<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbSetting extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'settings', 'parent_id', 'status', 'created_by'
    ];
}
