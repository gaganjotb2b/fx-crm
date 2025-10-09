<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResentVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data = [];
    public function __construct($data)
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
        $template = EmailTemplate::select('name')->where('use_for', 'resent-verification-email')->first();
        if ($template) {
            return $this->view('email.trader-admin.' . $template->name)
                ->with([
                    'accountActivationLink'         => $this->data['accountActivationLink'],
                    'emailSupport'     => $this->data['emailSupport'],
                    'phone1'           => $this->data['phone1'],
                    'companyName'      => $this->data['companyName'],
                    'website'          => $this->data['website'],
                    'copy_right'       => $this->data['copy_right'],
                    'emailCommon'       => $this->data['emailCommon'],
                    'clientName'       => $this->data['clientName'],
                    'authority'        => $this->data['authority'],
                    'license'        => $this->data['license'],
                    'loginUrl'        => $this->data['loginUrl'],
                ]);
        } else {
            return $this->view('email.trader-admin.mail-resend-verification-email');
        }
    }
}
