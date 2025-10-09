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
        if (!Schema::hasTable('other_transactions')) {
            Schema::create('other_transactions', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('transaction_type', 20)->comment('Neteller, Skrill, Crypto');
                $table->string('crypto_type', 5)->nullable()->comment('BTC, ETH, LTC, USDT');
                $table->string('crypto_instrument', 50)->nullable()->comment('BTC, ETH, LTC, USDT');
                $table->string('block_chain', 100)->nullable()->comment('As Like ERC20, TRC20');
                $table->string('gateway_name')->nullable()->comment('use for m2pay');

                $table->string('crypto_address', 100)->nullable();

                $table->decimal('crypto_amount', 19, 8)->nullable();
                $table->string('account_name', 100)->nullable();
                $table->string('account_email', 100)->nullable();
                $table->string('payment_id')->nullable()->comment('m2pay payment ID');

                $table->string('status')->nullable()->comment('NEW, DONE,PENDING');
                $table->json('admin_log')->nullable();
                $table->string('ip_address')->nullable();
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
        Schema::dropIfExists('other_transactions');
    }
};
