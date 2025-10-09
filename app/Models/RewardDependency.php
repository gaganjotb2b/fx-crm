<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardDependency extends Model
{
    use HasFactory;
    public $timestamps = false;

    // Fillable attributes
    protected $fillable = ['reward_id', 'type', 'value'];  
}
