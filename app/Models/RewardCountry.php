<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardCountry extends Model
{
    use HasFactory;

    protected $table = 'reward_country'; // Set the table name

    public $timestamps = false;

    protected $fillable = ['reward_id', 'country_id'];    
}
