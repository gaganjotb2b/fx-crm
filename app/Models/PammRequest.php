<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PammRequest extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = "pamm_requests";
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'account',
        'username',
        'min_deposit',
        'max_deposit',
        'share_profit',
        'status',
        'approved_by',
        'admin_log',
        'approved_date'
    ];
}
