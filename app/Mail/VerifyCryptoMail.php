<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCryptoMail extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for', 'crypto-add-generate')->first();
        if ($template) {
            return $this->view('email.' . $template->name)
                ->with([
                    'name'              => $this->data['name'],
                    'admin'             => $this->data['admin'],
                    'admin_message'     => $this->data['admin_message'],
                    'support_email'     => $this->data['support_email'],
                ]);
        } else {
            return $this->view('email.mail-crypto-address-create')->with([
                'name'              => $this->data['name'],
                'admin'             => $this->data['admin'],
                'admin_message'     => $this->data['admin_message'],
                'support_email'     => $this->data['support_email'],
            ]);;
        }
    }
}
