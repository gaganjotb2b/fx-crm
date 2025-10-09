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
        try {
            Schema::create('payment_gateway_configs', function (Blueprint $table) {
                $table->id();
                $table->string('gateway_name')->nullable();
                $table->string('merchent_code')->nullable();
                $table->string('user_name')->nullable();
                $table->string('password')->nullable();

                $table->string('api_url')->nullable();
                $table->string('api_token')->nullable();
                $table->string('api_secret')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('created_by')->nullable();
                $table->json('admin_log')->nullable();
                $table->timestamps();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateway_configs');
    }
};
