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
        if (!Schema::hasTable('user_otp_settings')) {
        Schema::create('user_otp_settings', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->boolean('account_create')->default(1);
            $table->boolean('deposit')->default(1);
            $table->boolean('withdraw')->default(1);
            $table->boolean('transfer')->default(1);
            $table->unsignedBigInteger('user_id')->nullable()->comment('FK:Users(id)');
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
        Schema::dropIfExists('user_otp_settings');
    }
};
