<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $fillable = [
        'id',
        'aml_policy',
        'contact_us',
        'privacy_policy',
        'refund_policy',
        'terms_and_cond'
    ];
}
