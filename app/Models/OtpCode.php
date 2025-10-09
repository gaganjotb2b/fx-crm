<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;
    public $timestamp = true;
    protected $fillable = [
        'user_id',
        'code',
        'amount',
        'type',
        'bank_id',
        'properties',
        'email',
        'action_count',
    ];
    // get user
    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
