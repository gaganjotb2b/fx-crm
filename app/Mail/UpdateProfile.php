<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateProfile extends Mailable
{
    use Queueable, SerializesModels;
    protected $data = [];

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
        $template = EmailTemplate::select('name')->where('use_for', 'update-profile')->first();
        if ($template) {
            return $this->view('email.profile.' . $template->name)
                ->with([
                    'loginUrl'         => $this->data['loginUrl'],
                    'emailSupport'     => $this->data['emailSupport'],
                    'customMessage'    => $this->data['customMessage'],
                    'phone1'           => $this->data['phone1'],
                    'companyName'      => $this->data['companyName'],
                    'website'          => $this->data['website'],
                    'copy_right'       => $this->data['copy_right'],
                    'emailCommon'       => $this->data['emailCommon'],
                    'clientName'       => $this->data['clientName'],
                    'authority'        => $this->data['authority'],
                    'license'        => $this->data['license'],
                ]);
        } else {
            return $this->view('email.profile.mail-update-profile');
        }
    }
}
