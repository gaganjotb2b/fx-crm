<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadComment extends Model
{
    use HasFactory;
    
    public $timesptamp = true;
    protected $fillable = [
        'user_id',
        'client_id_number',
        'user_type',
        'note',
        'created_by',
        'updated_by'
    ];
}
