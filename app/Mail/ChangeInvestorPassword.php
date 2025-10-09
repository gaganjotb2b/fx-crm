<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeInvestorPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $data = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        $template = EmailTemplate::select('name')->where('use_for', 'change-investor-password')->first();
        if ($template) {
            return $this->view('email.' . $template->name)
                ->with([
                    'clientName' => $this->data['clientName'],
                    'clientEmail' => $this->data['clientEmail'],
                    'clientAccountNo' => $this->data['clientAccountNo'],
                    'clientInvestorPassword' => $this->data['clientInvestorPassword'],
                    'companyName' => $this->data['companyName'],
                    'website' => $this->data['website'],
                    'loginUrl' => $this->data['loginUrl'],
                    'emailSupport' => $this->data['emailSupport'],
                    'phone1' => $this->data['phone1'],
                    'emailCommon' => $this->data['emailCommon'],
                    'authority' => $this->data['authority'],
                    'license' => $this->data['license'],
                    'copy_right' => $this->data['copy_right'],
                ]);
        } else {
            return $this->view('email.mail-change-investor-password')
                ->with([
                    'clientName' => $this->data['clientName'],
                    'clientEmail' => $this->data['clientEmail'],
                    'clientAccountNo' => $this->data['clientAccountNo'],
                    'clientInvestorPassword' => $this->data['clientInvestorPassword'],
                    'companyName' => $this->data['companyName'],
                    'website' => $this->data['website'],
                    'loginUrl' => $this->data['loginUrl'],
                    'emailSupport' => $this->data['emailSupport'],
                    'phone1' => $this->data['phone1'],
                    'emailCommon' => $this->data['emailCommon'],
                    'authority' => $this->data['authority'],
                    'license' => $this->data['license'],
                    'copy_right' => $this->data['copy_right'],
                ]);
        }
    }
}
