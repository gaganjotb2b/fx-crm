<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VoucherGenerate extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for','voucher-generate')->first();
        if ($template) {
            return $this->view('email.voucher.'.$template->name)
            ->with([
                'name'              => $this->data['name'],
                'token_number'      => $this->data['token_number'],
                'amount'            => $this->data['amount'],
                'support_email'     => $this->data['support_email']
            ]);
        }
        else {
            return $this->view('email.voucher.mail-voucher-generate')->with([
                'name'              => $this->data['name'],
                'token_number'      => $this->data['token_number'],
                'amount'            => $this->data['amount'],
                'support_email'     => $this->data['support_email']
            ]);
        }
    }
}
