<?php

namespace App\Mail\transfer;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

class BalanceTransfer extends Mailable
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
        $template = EmailTemplate::select('name')->where('use_for','balance-transfer')->first();
        if ($template) {
            return $this->view('email.transfer.'.$template->name)
            ->with([
                'clientName'               =>$this->data['clientName'],
                'companyName'              => $this->data['companyName'],
                'website'                  => $this->data['website'],
                'emailCommon'              => $this->data['emailCommon'],
                'phone1'                   => $this->data['phone1'],
                'emailSupport'             => $this->data['emailSupport'],
                'clientDepositAmount'      => $this->data['clientDepositAmount'],
                'license'                  => $this->data['license'],
                'copy_right'               => $this->data['copy_right'],
                'authority'                => $this->data['authority'],
                
            ]);
        }
        else {
            return $this->view('email.transfer.mail-balance-transfer');
        }
    }
}
