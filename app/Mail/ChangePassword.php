<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;
class ChangePassword extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for','change-password')->first();
        if ($template) {
            return $this->view('email.trader-admin.'.$template->name)
                ->with([
                    'clientName' => $this->data['clientName'],
                    'clientUsername' => $this->data['clientUsername'],
                    'clientPassword' => $this->data['clientPassword'],
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
        else {
            return $this->view('email.trader-admin.mail-change-password')->with([
                'clientName' => $this->data['clientName'],
                'clientUsername' => $this->data['clientUsername'],
                'clientPassword' => $this->data['clientPassword'],
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
