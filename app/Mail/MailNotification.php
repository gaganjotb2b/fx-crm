<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $data = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data=[])
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
        $template = EmailTemplate::select('name')->where('use_for', 'user-notification')->first();
        if ($template) {
            return $this->view('email.'.$template->name)
                ->with([
                    'clientName'        => $this->data['clientName'],
                    'emailSupport'      => $this->data['emailSupport'],
                    'amount'            =>$this->data['amount'],
                    'notification_type' =>$this->data['notification_type'],
                    'admin_name'        =>$this->data['admin_name']
                ]);
        } else {
            return $this->view('email.mail-user-notification')->with([
                'clientName'        => $this->data['clientName'],
                'emailSupport'      => $this->data['emailSupport'],
                'amount'            =>$this->data['amount'],
                'notification_type' =>$this->data['notification_type'],
                'admin_name'        =>$this->data['admin_name']
            ]);;
        }
    }
}
