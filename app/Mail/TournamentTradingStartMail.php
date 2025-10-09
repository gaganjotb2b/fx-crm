<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TournamentTradingStartMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tournament Trading Start')->view('email.mail-tournament-trading-start')
            ->with([
                'name'              => $this->data['name'],
                'master-admin'      => $this->data['master-admin'],
                'tournament_message' => $this->data['tournament_message'],
                'support_email'     => $this->data['support_email'],
            ]);
    }
}
