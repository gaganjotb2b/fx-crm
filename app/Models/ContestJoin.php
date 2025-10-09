<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestJoin extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'account_number',
        'contest_id',
        'total_profit',
        'total_lot',
        'position',
        'frozen_equity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
