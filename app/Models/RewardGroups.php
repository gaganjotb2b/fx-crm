<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardGroups extends Model
{
    use HasFactory;
    public $timestamps = false;

    // Fillable attributes
    protected $fillable = ['reward_id', 'group_id'];    

}
