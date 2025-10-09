<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'contest_name',
        'allowed_for',
        'kyc',
        'contest_type',
        'is_global',
        'credit_type',
        'expire_after',
        'expire_type',
        'max_contest',
        'min_join',
        'start_date',
        'end_date',
        'contest_prices',
        'description',
        'user_type',
        'client_group',
        'ib_group',
        'popup_image',
        'status',
        'results_announced',
        'hidden_groups'
    ];

    public function contestJoins()
    {
        return $this->hasMany(ContestJoin::class, 'contest_id');
    }
}
