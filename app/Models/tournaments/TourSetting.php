<?php

namespace App\Models\tournaments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourSetting extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'tour_name', 
        'organization_name', 
        'client_group_id', 
        'min_deposit', 
        'group_trading_duration', 
        'start_date', 
        'end_date', 
        'prize_1', 
        'prize_2', 
        'prize_3', 
        'prize_4', 
        'registration_period'
    ];
}
