<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CryptoMailForITCorner extends Mailable
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
        if ((array_key_exists('transaction', $this->data) == true) && ($this->data['transaction'] == 'crypto_withdraw')) {
            $template = EmailTemplate::select('name')->where('use_for', 'crypto-withdraw-notify-for-itc')->first();
            if ($template) {
                return $this->subject('Crypto withdraw notification')->view('email.' . $template->name)
                    ->with([
                        'name'              => $this->data['name'],
                        'master-admin'      => $this->data['master-admin'],
                        'it_corner_message' => $this->data['it_corner_message']
                    ]);
            } else {
                return $this->subject('Crypto withdraw notification')->view('email.mail-crypto-withdraw-notification-for-itcorner');
            }
        } else {
            $template = EmailTemplate::select('name')->where('use_for', 'crypto-add-for-it-corner')->first();
            if ($template) {
                return$this->subject('Crypto address notification')->view('email.' . $template->name)
                    ->with([
                        'name'              => $this->data['name'],
                        'master-admin'      => $this->data['master-admin'],
                        'it_corner_message' => $this->data['it_corner_message'],
                        'support_email'     => $this->data['support_email'],
                    ]);
            } else {
                return $this->subject('Crypto address notification')->view('email.mail-crypto-address-generate')
                    ->with([
                        'name'              => $this->data['name'],
                        'master-admin'      => $this->data['master-admin'],
                        'it_corner_message' => $this->data['it_corner_message'],
                        'support_email'     => $this->data['support_email'],
                    ]);
            }
        }
    }
}
