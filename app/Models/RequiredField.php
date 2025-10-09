<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredField extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'gender',
        'password',
        'country',
        'state',
        'city',
        'zip_code',
        'address'
    ];
}
