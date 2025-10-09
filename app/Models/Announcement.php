<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    public $timestamp = true;
    use HasFactory;
    protected $fillable = [
        'title',
        'comment',
        'dashboard',
        'status'
    ];
}
