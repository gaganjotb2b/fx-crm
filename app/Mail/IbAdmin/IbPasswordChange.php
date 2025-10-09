<?php

namespace App\Mail\IbAdmin;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IbPasswordChange extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for', 'ib-password-change')->first();
        if ($template) {
            return $this->view('email.ib-admin.' . $template->name)
                ->with([
                    'name'                     => $this->data['name'],
                    'account_email'            => $this->data['account_email'],
                    'login_url'                => $this->data['login_url'],
                    'password'                 => $this->data['password'],
                    'companyName'              => $this->data['companyName'],
                    'website'                  => $this->data['website'],
                    'emailCommon'              => $this->data['emailCommon'],
                    'phone1'                   => $this->data['phone1'],
                    'license'                  => $this->data['license'],
                    'copy_right'               => $this->data['copy_right'],
                    'authority'                => $this->data['authority'],
                ]);
        } else {
            return $this->view('email.ib-admin.mail-ib-password-change')->with([
                'name'                     => $this->data['name'],
                'account_email'            => $this->data['account_email'],
                'login_url'                => $this->data['login_url'],
                'password'                 => $this->data['password'],
                'companyName'              => $this->data['companyName'],
                'website'                  => $this->data['website'],
                'emailCommon'              => $this->data['emailCommon'],
                'phone1'                   => $this->data['phone1'],
                'license'                  => $this->data['license'],
                'copy_right'               => $this->data['copy_right'],
                'authority'                => $this->data['authority'],
            ]);
        }
    }
}
