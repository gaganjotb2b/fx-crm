<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketReply extends Model
{
    use HasFactory;
    protected $table = "ticket_reply";
    public $timesptamp = true;
    protected $fillable = [
        'ticket_id',
        'reply_description',
        'replay_by',
        'attch_id',
        'created_by',
        'updated_by',
    ];
    public function replyBy()
    {
        return $this->belongsTo(User::class, 'replay_by', 'id');
    }
    public function replyOf() {
        return $this->belongsTo(tickets::class,'ticket_id','id');
    }
    public function attachment()
    {
        return $this->belongsTo(ticketAttachment::class, 'attch_id', 'id');
    }
}
