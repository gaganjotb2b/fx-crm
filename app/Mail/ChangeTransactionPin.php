<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class ChangeTransactionPin extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for', 'change-transaction-pin')->first();
        if ($template) {
            return $this->view('email.' . $template->name)
                ->with([
                    'name'              => $this->data['clientName'],
                    'admin'              => 'Super admin',
                    'account_email'     => $this->data['clientUsername'],
                    'login_url'         => $this->data['accountActivationLink'],
                    'support_email'     => $this->data['emailSupport'],
                    'transaction_pin'   => $this->data['clientTransactionPassword'],
                ]);
        } else {
            return $this->view('email.mail-change-transaction-pin');
        }
    }
}
