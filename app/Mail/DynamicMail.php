<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\SoftwareSetting;
use Database\Seeders\SoftwareSetings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data = [];
    public $user_for = [];
    public $mail_template;
    public $temp_dir;
    public $mail_subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        if (array_key_exists('use_for', $data)) {
            $this->user_for = $data['use_for'];
        }
        if (array_key_exists('mail_subject',$data)) {
            $this->mail_subject = $data['mail_subject'];
        }
        // choose mail tmplate verssion
        $temp_version = SoftwareSetting::select('email_template')->first();
        if ($temp_version) {
            $this->temp_dir = ($temp_version->email_template === 'v2') ? 'email/email2' : 'email';
        } else {
            $this->temp_dir = 'email/email2';
        }
        switch ($data['use_for']) {
            case 'balance-transfer':
                $this->mail_template = $this->temp_dir . '.mail-balance-transfer';
                $this->mail_subject = 'Balance Transfer';
                break;
            case 'ib-to-trader-transfer':
                $this->mail_template = $this->temp_dir . '.mail-ib-to-trader-balance-transfer';
                $this->mail_subject = 'Balance Transfer';
                break;
            case 'ib-to-ib-transfer':
                $this->mail_template = $this->temp_dir . '.mail-ib-to-ib-balance-transfer';
                $this->mail_subject = 'Balance Transfer';
                break;
            case 'wta-transfer':
                $this->mail_template = $this->temp_dir . '.mail-wta-transfer';
                $this->mail_subject = 'Wallet to Account Transfer';
                break;
            case 'atw-transfer':
                $this->mail_template = $this->temp_dir . '.mail-atw-transfer';
                $this->mail_subject = 'Account to Wallet Transfer';
                break;
            case 'trader-to-trader-transfer':
                $this->mail_template = $this->temp_dir . '.mail-trader-to-trader-transfer';
                $this->mail_subject = 'Wallet to Other Transfer';
                break;
            case 'trader-to-ib-transfer':
                $this->mail_template = $this->temp_dir . '.mail-trader-to-ib-transfer';
                $this->mail_subject = 'Wallet to Other Transfer';
                break;
            case 'otp-verification':
                $this->mail_template = $this->temp_dir . '.mail-otp';
                $this->mail_subject = 'OTP Verification';
                break;
            case 'trader-registration':
                $this->mail_template = $this->temp_dir . '.mail-trader-registration';
                $this->mail_subject = 'Trader Registration';
                break;
            case 'withdraw-request':
                $this->mail_template = $this->temp_dir . '.mail-withdraw-request-recieved';
                $this->mail_subject = 'Withdraw Request';
                break;
            case 'crypto-withdraw-request':
                $this->mail_template = $this->temp_dir . '.mail-crypto-withdraw-request-recieved';
                $this->mail_subject = 'Withdraw Request';
                break;
            case 'crypto-withdraw-notify-for-itcorner':
                $this->mail_template = $this->temp_dir . '.mail-crypto-withdraw-notification-for-itcorner';
                $this->mail_subject = 'Withdraw Request';
                break;
            case 'bank-deposit-request':
                $this->mail_template = $this->temp_dir . '.mail-bank-deposit-request';
                $this->mail_subject = 'Bank Deposit Request';
                break;
            case 'crypto-deposit-request':
                $this->mail_template = $this->temp_dir . '.mail-crypto-deposit-request';
                $this->mail_subject = 'Crypto Deposit Request';
                break;
            case 'trader-password-change':
                $this->mail_template = $this->temp_dir . '.mail-trader-password-change';
                $this->mail_subject = 'Password Changes';
                break;
            case 'ib-password-change':
                $this->mail_template = $this->temp_dir . '.ib-admin.mail-ib-password-change';
                $this->mail_subject = 'Password Changes';
                break;
            case 'change-password':
                $this->mail_template = $this->temp_dir . '.mail-change-password';
                $this->mail_subject = 'Password Changes';
                break;
            case 'change-transaction-password':
                $this->mail_template = $this->temp_dir . '.mail-change-transaction-pin';
                $this->mail_subject = 'Transaction Pin Changes';
                break;
            case 'send-transaction-pin':
                $this->mail_template = $this->temp_dir . '.mail-send-transaction-pin';
                $this->mail_subject = 'Forgot Transaction Pin';
                break;
            case 'reset-transaction-password':
                $this->mail_template = $this->temp_dir . '.mail-reset-transaction-pin';
                $this->mail_subject = 'Transaction Pin Reset';
                break;
            case 'resend-account-credentials':
                $this->mail_template = $this->temp_dir . '.mail-resend-account-credentials';
                $this->mail_subject = 'Your Account Details';
                break;
            case 'reset-password':
                $this->mail_template = $this->temp_dir . '.mail-reset-password';
                $this->mail_subject = 'Password Reset';
                break;
            case 'resent-verification-email':
                $this->mail_template = $this->temp_dir . '.mail-resend-verification-email';
                $this->mail_subject = 'Profile Activation';
                break;
            case 'signup':
                $this->mail_template = $this->temp_dir . '.mail-signup';
                $this->mail_subject = 'Welcome mail';
                break;
            case 'open-demo-account':
                $this->mail_template = $this->temp_dir . '.mail-open-demo-account';
                $this->mail_subject = 'Your Trading account created';
                break;
            case 'open-trading-account':
                $this->mail_template = $this->temp_dir . '.mail-open-demo-account';
                $this->mail_subject = 'Your Trading account created';
                break;
            case 'change-master-password':
                $this->mail_template = $this->temp_dir . '.mail-change-master-password';
                $this->mail_subject = 'Your Master password changes';
                break;
            case 'reset-master-password':
                $this->mail_template = $this->temp_dir . '.mail-reset-master-password';
                $this->mail_subject = 'Your Master password reset';
                break;
            case 'add-credit':
                $this->mail_template = $this->temp_dir . '.mail-add-credit';
                $this->mail_subject = 'Add Credit';
                break;
            case 'add-credit-mail':
                $this->mail_template = $this->temp_dir . '.mail-add-credit';
                $this->mail_subject = 'Add Credit';
                break;
            case 'deduct-credit-mail':
                $this->mail_template = $this->temp_dir . '.mail-deduct-credit';
                $this->mail_subject = 'Deduct Credit';
                break;
            case 'balance-deduct':
                $this->mail_template = $this->temp_dir . '.mail-deduct-balance';
                $this->mail_subject = 'Deduct Balance From you';
                break;
            case 'change-leverage':
                $this->mail_template = $this->temp_dir . '.mail-change-leverage';
                $this->mail_subject = 'Your leverage changed';
                break;
            case 'bank-account-add':
                $this->mail_template = $this->temp_dir . '.mail-bank-account-add';
                $this->mail_subject = 'Your Bank account Added';
                break;
            case 'combine-ib-app':
                $this->mail_template = $this->temp_dir . '.mail-combine-ib-app';
                $this->mail_subject = 'IB Access';
                break;
                // mail update profile
            case 'update-profile':
                $this->mail_template = $this->temp_dir . '.mail-update-profile';
                $this->mail_subject = 'Update Profile';
                break;
                //mail ib registration activation
            case 'ib-registration':
                $this->mail_template = $this->temp_dir . '.mail-ib-regitration';
                $this->mail_subject = 'IB Registration';
                break;
            case 'kyc-approve-request':
                $this->mail_template = $this->temp_dir . '.mail-kyc-approve-request';
                $this->mail_subject = 'KYC Approve';
                break;
            case 'kyc-decline':
                $this->mail_template = $this->temp_dir . '.mail-kyc-decline';
                $this->mail_subject = 'KYC Decline';
                break;
            case 'deposit-request-approve':
                $this->mail_template = $this->temp_dir . '.mail-approve-deposit-request';
                $this->mail_subject = 'Deposit Request Approve';
                break;
            case 'balance-approve':
                $this->mail_template = $this->temp_dir . '.mail-balance-approve-request';
                $this->mail_subject = 'Transaction Request Approve';
                break;
            case 'balance-decline':
                $this->mail_template = $this->temp_dir . '.mail-balance-decline-request';
                $this->mail_subject = 'Transaction Request Declined';
                break;
            case 'decline-deposit-request':
                $this->mail_template = $this->temp_dir . '.mail-decline-deposit-request';
                $this->mail_subject = 'Deposit Request Declined';
                break;
            case 'change-investor-password':
                $this->mail_template = $this->temp_dir . '.mail-change-investor-password';
                $this->mail_subject = 'Investor password changes';
            case 'reset-investor-password':
                $this->mail_template = $this->temp_dir . '.mail-reset-investor-password';
                $this->mail_subject = 'Investor password reset';
                break;
            case 'ib-kyc-approved':
                $this->mail_template = $this->temp_dir . '.mail-ib-kyc-approved-request';
                $this->mail_subject = 'KYC Request Approved';
                break;
            case 'ib-kyc-declined':
                $this->mail_template = $this->temp_dir . '.mail-ib-kyc-decline-request';
                $this->mail_subject = 'KYC Request Declined';
                break;
            case 'ib-balance-transfer-approved':
                $this->mail_template = $this->temp_dir . '.mail-ib-transfer-approve';
                $this->mail_subject = 'Balance Transfer Approved';
                break;
            case 'ib-withdraw-approve':
                $this->mail_template = $this->temp_dir . '.mail-ib-withdraw-approve';
                $this->mail_subject = 'Withdraw Request Approved';
                break;
            case 'withdraw-approve':
                $this->mail_template = $this->temp_dir . '.mail-withdraw-approve-request';
                $this->mail_subject = 'Withdraw Request Approved';
                break;
            case 'withdraw-decline':
                $this->mail_template = $this->temp_dir . '.mail-withdraw-decline-request';
                $this->mail_subject = 'Withdraw Request Decline';
                break;
            case 'ib-withdraw-decline':
                $this->mail_template = $this->temp_dir . '.mail-ib-withdraw-declined';
                $this->mail_subject = 'Withdraw Request Declined';
                break;
            case 'trading-account-reset-password':
                $this->mail_template = $this->temp_dir . '.mail-trading-account-reset-password';
                $this->mail_subject = 'Reset Password';
                break;
            case 'trader-reset-password':
                $this->mail_template = $this->temp_dir . '.mail-trader-reset-password';
                $this->mail_subject = 'Reset Password';
                break;
                // combine crm mail
            case 'convert-to-ib':
                $this->mail_template = $this->temp_dir . '.mail-convert-to-ib';
                $this->mail_subject = 'Grant Access for IB';
                break;
            case 'request-for-ib':
                $this->mail_template = $this->temp_dir . '.mail-request-for-ib';
                $this->mail_subject = 'You requested for IB';
                break;
                // decline ib request
            case 'decline-ib-request':
                $this->mail_template = $this->temp_dir . '.mail-ib-request-decline';
                $this->mail_subject = 'Your ib request decline';
                break;
            case 'remove-from-ib':
                $this->mail_template = $this->temp_dir . '.mail-remove-from-ib';
                $this->mail_subject = 'Remove access from IB';
                break;
            case 'admin-registration':
                $this->mail_template = $this->temp_dir . '.mail-admin-registration';
                $this->mail_subject = 'Admin Registration';
                break;
            case 'admin-activation':
                $this->mail_template = $this->temp_dir . '.mail-admin-activation';
                $this->mail_subject = 'Admin Activation';
                break;
            case 'manager-registration':
                $this->mail_template = $this->temp_dir . '.mail-manager-registration';
                $this->mail_subject = 'Manager Registration';
                break;
            case 'wallet-withdraw':
                $this->mail_template = $this->temp_dir . '.mail-wallet-withdraw';
                $this->mail_subject = 'Withdraw from your wallet';
                break;
            case 'wallet-deposit':
                $this->mail_template = $this->temp_dir . '.mail-wallet-deposit';
                $this->mail_subject = 'Deposit to your wallet';
                break;
            case 'update-withdraw-amount':
                $this->mail_template = $this->temp_dir . '.mail-user-withdraw-request-amount';
                $this->mail_subject = 'Update withdraw amount';
                break;
            case 'update-deposit-amount':
                $this->mail_template = $this->temp_dir . '.mail-user-deposit-request-amount';
                $this->mail_subject = 'Update deposit amount';
                break;
            case 'custom-mail':
                $this->mail_template = $this->temp_dir . '.mail-custom';
                $this->mail_subject = 'Important Update: Data Shifting to CRM and Copier Issues';
                break;
            case 'admin-notification':
                $this->mail_template = $this->temp_dir . '.mail-user-notification';                
                break;
            default:
                $this->mail_template = $this->temp_dir . '.custom';
                $this->mail_subject = 'Mail From ' . ucwords(get_company_name());
                break;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = EmailTemplate::select('name')->where('use_for', $this->user_for)->first();
        if ($template) {
            return $this->subject($this->mail_subject)->view($this->temp_dir . '.' . $template->name)
                ->with($this->data);
        } else {
            return $this->subject($this->mail_subject)->view($this->mail_template)
                ->with($this->data);
        }
    }
}
