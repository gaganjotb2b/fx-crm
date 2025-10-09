<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPverificationMail extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for', 'c')->first();
        if ($template) {
            return $this->subject('OTP Verfication Mail')->view('email.' . $template->name)
                ->with([
                    'otp'                => $this->data['otp'],
                ]);
        } else {
            return $this->view('email.mail-otp')->with([
                'otp'                      => $this->data['otp'],
                'clientName'               => $this->data['clientName'],
                'companyName'              => $this->data['companyName'],
                'website'                  => $this->data['website'],
                'emailCommon'              => $this->data['emailCommon'],
                'phone1'                   => $this->data['phone1'],
                'license'                  => $this->data['license'],
                'copy_right'               => $this->data['copy_right'],
                'authority'                => $this->data['authority'],
            ]);;
        }
    }
}
