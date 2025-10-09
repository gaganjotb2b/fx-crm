<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TraderRegistrationMail extends Mailable
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
        $data = [
            'emailCommon'                => $this->data['emailCommon'],
            'clientName'                 => $this->data['clientName'],
            'companyName'                => $this->data['companyName'],
            'loginUrl'                   => $this->data['loginUrl'],
            'clientEmail'                => $this->data['clientEmail'],
            'regId'                      => $this->data['regId'],
            'clientPassword'             => $this->data['clientPassword'],
            'clientTransactionPassword'  => $this->data['clientTransactionPassword'],
            'server'                     => $this->data['server'],
            'clientMt4AccountNumber'     => $this->data['clientMt4AccountNumber'],
            'clientMt4AccountPassword'   => $this->data['clientMt4AccountPassword'],
            'clientMt4InvestorPassword'  => $this->data['clientMt4InvestorPassword'],
            'mtdl'                       => $this->data['mtdl'],
            'emailSupport'               => $this->data['emailSupport'],
            'phone1'                     => $this->data['phone1'],
            'website'                    => $this->data['website'],
            'authority'                  => $this->data['authority'],
            'license'                    => $this->data['license'],
            'copy_right'                 => $this->data['copy_right'],
            'site_logo'                  => $this->data['site_logo'],
            'linkedin_link'              => $this->data['linkedin_link'],
            'youtube_link'               => $this->data['youtube_link'],
            'twitter_link'               => $this->data['twitter_link'],
        ];
        $template = EmailTemplate::select('name')->where('use_for', 'trader-registration')->first();
        if ($template && $template->name != null) {
            return $this->view('email.' . $template->name)
                ->with($data);
        } else {
            return $this->view('email.mail-trader-registration')->with($data);
        }
    }
}
