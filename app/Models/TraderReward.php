<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderReward extends Model
{
    use HasFactory;

    protected $table = 'trader_reward'; // Set the table name

    protected $fillable = ['reward_id', 'user_id', 'status'];    
}
