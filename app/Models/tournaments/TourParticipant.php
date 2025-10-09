<?php

namespace App\Models\tournaments;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourParticipant extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'user_id', 
        'tournament_id', 
        'account_id', 
        'account_num', 
        'group_id', 
        'group1_status',
        'group1_profit',
        'group1_volume',
        'status',
    ];

    public function group()
    {
        return $this->belongsTo(TourGroup::class, 'group_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function accountDeposit()
    {
        return $this->hasMany(AccountDeposit::class, 'account_id');
    }

}
