<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadManagement extends Model
{
    use HasFactory;

    public $timesptamp = true;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'zip',
        'account',
        'have_task',
        'am_id',
        'manager_id',
        'desk_id',
        'category_id',
        'created_by',
        'update_by'
    ];
}
