<?php

namespace App\Models\tournaments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourGroup extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'group_name', 
        'round', 
        'tournament_id', 
        'max_participants',
        'start_trading',
        'duration',
        'status'
    ];
    public function participants()
    {
        return $this->hasMany(TourParticipant::class, 'group_id');
    }
    
}
