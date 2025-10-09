<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeclineRequest extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for','decline-request')->first();
        if ($template) {
            return $this->view('email.'.$template->name)
            ->with([
                'name'              => $this->data['name'],
                'account_email'     => $this->data['account_email'],
                'admin'             => $this->data['admin'],
                'login_url'         => $this->data['login_url'],
                'support_email'     => $this->data['support_email']
            ]);
        }
        else {
            return $this->view('email.mail-decline-deposit-request');
        }
    }
}
