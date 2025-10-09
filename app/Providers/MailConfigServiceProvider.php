<?php

namespace App\Providers;

use Config;
use App\Models\admin\SystemConfig;
use App\Models\SmtpSetup;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        try {
            if (Schema::hasTable('smtp_setups')) {
                $mail = DB::table('smtp_setups')->select()->first();
                $system_config = DB::table('system_configs')->select('auto_email')->first();
                if ($mail) //checking if table is not empty
                {
                    $config = array(
                        'driver'     => $mail->mail_driver,
                        'host'       => $mail->host,
                        'port'       => $mail->port,
                        'from'       => array('address' => $system_config->auto_email, 'name' => config('app.name')),
                        'encryption' => $mail->mail_encryption,
                        'username'   => $mail->mail_user,
                        'password'   => $mail->mail_password,
                        'sendmail'   => '/usr/sbin/sendmail -bs',
                        'pretend'    => false,
                    );
                    FacadesConfig::set('mail', $config);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
