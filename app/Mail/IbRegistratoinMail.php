<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Services\CompanyInfoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IbRegistratoinMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
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
        $data = CompanyInfoService::company_info();
        $data['user_name'] = $this->data['user_name'];
        $data['user_email'] = $this->data['user_email'];
        $data['password'] = $this->data['password'];
        $data['transaction_password'] = $this->data['transaction_password'];
        $data['platform'] = $this->data['platform'];
        $data['activation_link'] = $this->data['activation_link'];
        $template = EmailTemplate::select('name')->where('use_for', 'ib-registration')->first();
        if ($template && $template->name != null) {
            return $this->view('email.' . $template->name)
                ->with($data);
        } else {
            return $this->view('email.mail-ib-regitration')->with($data);
        }
    }
}
