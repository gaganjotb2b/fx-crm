<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tickets extends Model
{
    use HasFactory;
    protected $table = "tickets";
    public $timesptamp = true;
    protected $fillable = [
        'user_id',
        'user_type',
        'subject',
        'description',
        'priority',
        'fa',
        'asign_to',
        'comment',
        'attch_id',
        'created_by',
        'updated_by',
        'status',
        'created_at',
        'updated_at'
    ];
    public function attachment()
    {
        return $this->belongsTo(ticketAttachment::class, 'attch_id', 'id');
    }
    public function replyTicket()
    {
        return $this->hasMany(ticketReply::class, 'ticket_id', 'id');
    }
}
