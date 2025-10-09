<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtSerial extends Model
{
    use HasFactory;
    protected $table = 'mt_serial';
    public $timestamp = true;
    protected $fillable = [
        'login_start',
        'login_end',
        'last',
        'login_gen',
        'server',
    ];
}
