<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'amount', 'start_date', 'end_date', "is_kyc", "is_global", "country_id", "user_id", "is_admin"];

}
