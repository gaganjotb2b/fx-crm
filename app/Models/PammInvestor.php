<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PammInvestor extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamp = true;
    protected $fillable = [
        'user_id',
        'pamm_id',
        'max_trade',
        'status',
    ];
    public function pammProfile():BelongsTo {
        return $this->belongsTo(PammUser::class,'pamm_id','id');
    }
    public function user():BelongsTo {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
