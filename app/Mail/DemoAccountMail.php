<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoAccountMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data = [])
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
        $data = [
            'clientName'                     => $this->data['clientName'],
            'clientEmail'                    => $this->data['clientEmail'],
            'clientUsername'                 => $this->data['clientUsername'],
            'clientPassword'                 => $this->data['clientPassword'],
            'clientTransactionPassword'      => $this->data['clientTransactionPassword'],
            'clientMt4AccountNumber'         => $this->data['clientMt4AccountNumber'],
            'clientMt4AccountPassword'       => $this->data['clientMt4AccountPassword'],
            'clientMt4InvestorPassword'      => $this->data['clientMt4InvestorPassword'],
            'server'                         => $this->data['server'],
            'companyName'                   => $this->data['companyName'],
            'website'                       => $this->data['website'],
            'emailCommon'                   => $this->data['emailCommon'],
            'phone1'                        => $this->data['phone1'],
            'license'                       => $this->data['license'],
            'copy_right'                    => $this->data['copy_right'],
            'authority'                     => $this->data['authority'],
        ];
        $template = EmailTemplate::select('name')->where('use_for','open-demo-account')->first();
        if ($template) {
            return $this->subject('Mail for Trading Account Creation')->view('email.'.$template->name)
            ->with($data);
        }
        else {
            return $this->subject('Mail for Trading Account Creation')->view('email.mail-open-demo-account')->with($data);
        }
    }
}
