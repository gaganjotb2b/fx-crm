<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('security_settings')) {
            Schema::create('security_settings', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->boolean('withdraw_otp')->default(1)->comment('0 for otp stop, 1 for otp needed');
                $table->boolean('deposit_otp')->default(1)->comment('0 for otp stop, 1 for otp needed');
                $table->boolean('t_to_t_otp')->default(1)->comment('0 for otp stop, 1 for otp needed for trader to trader');
                $table->boolean('t_to_ib_otp')->default(1)->comment('0 for otp stop, 1 for otp needed for trader to ib');
                $table->boolean('wta_otp')->default(1)->comment('0 for otp stop, 1 for otp needed for trader to ib');
                $table->boolean('atw_otp')->default(1)->comment('0 for otp stop, 1 for otp needed for trader to ib');
               
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security_settings');
    }
};
