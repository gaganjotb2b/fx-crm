<?php

namespace App\Models;

use App\Services\api\FileApiService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketAttachment extends Model
{
    use HasFactory;
    protected $table = "ticket_attachment";
    public $timesptamp = true;
    protected $fillable = [
        'path',
        'created_by',
        'updated_by',
    ];
    public function getPathAttribute($value)
    {
        // Add your prefix to the image URL
        if ($value) {
            $prefix = FileApiService::publi_url();
            return $prefix . $value;
        }

        return $value;
    }
}
