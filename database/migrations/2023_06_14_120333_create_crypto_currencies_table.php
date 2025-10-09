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
            Schema::create('crypto_currencies', function (Blueprint $table) {
                $table->id();
                $table->string('symbol')->nullable()->comment('cryptocurrency symbol like USDT,USDC(block chain)');
                $table->string('currency')->nullable()->comment('crypto currency like as ERC20');
                $table->string('payment_currency')->nullable()->comment('LIKE as USX');
                $table->string('created_by')->nullable()->comment('FK=>Users');
                $table->string('ip_address')->nullable()->comment('creator ip address');
                $table->json('admin_log')->nullable();
                $table->enum('status', ['active', 'disable', 'pending'])->default('active');
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
        Schema::dropIfExists('crypto_currencies');
    }
};
