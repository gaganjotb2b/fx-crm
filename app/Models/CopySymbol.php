<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CopySymbol extends Model
{
    protected $table="copy_symbols";
    use HasFactory;
    protected $fillable=[
        'symbol',
        'symbol_org',
        'title',
        'comm',
        'ib_rebate',
        'group_name',
        'group_id',
        'added_by',
        'visible',
    ];
}
