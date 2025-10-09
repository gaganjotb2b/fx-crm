<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PopupImage extends Model
{
    public $timestamps = true;
    use HasFactory, HasRoles;
    protected $fillable = [
        'image',
        'issue_date',
        'expire_date',
        'user_type',
        'status'
    ];
}
