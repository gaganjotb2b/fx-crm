<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Jenssegers\Mongodb\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class CopySlave extends Model
{
    use HasFactory;
    protected $connection = 'alternate';
    protected $collection = 'copy_slaves';
    /**  
     * The attributes which are mass assigned will be used.  
     *  
     * It will return @var array  
     */
    protected $fillable = [
        'master',
        'slave',
        'allocation',
        'type',
        'status',
        'max_number_of_trade',
        'max_trade_volume',
        'min_trade_volume',
        'total_loss',
        'total_copied',
        'created_at',
        'updated_at',
    ];
}
