<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CryptoAlert extends Mailable
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
        return $this->view('email.mail-crypto-address-generate')
            ->with([
                'name'              => $this->data['name'],
                'master-admin'      => $this->data['master-admin'],
                'it_corner_message' => $this->data['it_corner_message'],
                'support_email'     => $this->data['support_email'],
            ]);
    }
}
