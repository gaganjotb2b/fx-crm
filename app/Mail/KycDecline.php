<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class KycDecline extends Mailable
{
    use Queueable, SerializesModels;
    private $data = [];
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
        $template = EmailTemplate::select('name')->where('use_for','kyc decline')->first();
        if ($template) {
            return $this->view('email.mail-kyc-decline')
                ->with([
                    'name' => $this->data['name'],
                    'account_email' => $this->data['account_email'],
                    'admin' => $this->data['admin'],
                    'login_url' => $this->data['login_url'],
                    'support_email' => $this->data['support_email'],
                    'message_custom' => $this->data['message_custom'],
                    'phone' => $this->data['phone']
                ]);
        }
        else {
            return $this->view('email.mail-change-password');
        }
    }
}
