<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionsTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'action_type',
        'action_status',
        'notification',
        'description',
        'notify_for',
        'action_date',
        'created_by',
        'updated_by',
    ];
}
