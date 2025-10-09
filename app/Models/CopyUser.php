<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Jenssegers\Mongodb\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\Model as Eloquent;  

class CopyUser extends Model
{
    use HasFactory;
    protected $connection = 'alternate';
    protected $collection = 'copy_users';
    /**  
     * The attributes which are mass assigned will be used.  
     *  
     * It will return @var array  
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'account',
        'country',
        'min_deposit',
        'max_deposit',
        'share_profit',
        'status',
        'brocker_name',
        'created_at',
        'updated_at',
    ];
    // relation to copy trades
    public function copyTrade()
    {
        return $this->hasMany(CopyTrade::class, 'Login', 'account');
    }
}
