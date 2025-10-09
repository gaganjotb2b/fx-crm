<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company_links extends Model
{
    use HasFactory;
    protected $table = "company_links";
    public $timesptamp = true;
    protected $fillable = [
        'aml_policy',
        'contact_us',
        'privacy_policy',
        'refund_policy',
        'terms_condition',
        'created_at',
        'updated_at',
    ];
}
