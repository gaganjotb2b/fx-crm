<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\SoftDeletes;

class PammProfitShare extends Model
{
    use HasFactory;
    // use HasFactory, SoftDeletes;
    public $timestamp = true;
    protected $fillable = [
        'login', 
        'ticket', 
        'profit', 
        'open_time', 
        'close_time', 
        'pamm_id', 
        'investor_id', 
        'share_type', 
        'shared_amount', 
        'created_at', 
        'updated_at'
    ];
}
