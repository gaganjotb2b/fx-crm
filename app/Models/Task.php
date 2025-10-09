<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public $timesptamp = true;
    protected $fillable = [
        'task_name',
        'user_id',
        'open_date',
        'reminder_time',
        'status',
        'created_by',
        'updated_by'
    ];
}
