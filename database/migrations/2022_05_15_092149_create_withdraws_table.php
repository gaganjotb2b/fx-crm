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
        if (!Schema::hasTable('withdraws')) {
            Schema::create('withdraws', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->index('user_id');
                $table->string('transaction_id', 60)->nullable();
                $table->string('transaction_type', 20)->nullable()->comment('Bank, Other(Skrill, Neteller, Crypto)');
                $table->unsignedBigInteger('bank_account_id')->nullable();
                $table->unsignedBigInteger('other_transaction_id')->nullable();
                $table->double('amount')->default(0);
                $table->unsignedBigInteger('trading_account')->nullable();
                $table->enum('withdraw_option', ['account', 'wallet'])->default('wallet');
                $table->unsignedBigInteger('internal_transfer')->nullable();
                $table->json('client_log')->nullable();
                $table->double('charge')->nullable();
                $table->unsignedBigInteger('charge_id')->nullable()->comment('references by transaction_settings table');
                $table->index('charge_id');
                $table->enum('approved_status', ['A', 'P', 'D'])->default('P')->comment('A for approved, P for pending, D for Decline');
                $table->string('note', 100)->nullable();
                $table->string('currency', 60)->nullable()->comment('local currency');
                $table->double('local_currency')->nullable()->comment('local currency rate');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->index('approved_by');
                $table->json('admin_log')->nullable();
                $table->timestamp('approved_date')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
                $table->foreign('other_transaction_id')->references('id')->on('other_transactions')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('charge_id')->references('id')->on('transaction_settings')->onDelete('cascade');
                $table->enum('wallet_type', ['trader', 'ib'])->nullable()->comment('withdraw from trader wallet or IB wallet');
                $table->enum('created_by', ['system', 'admin', 'manager', 'system_admin'])->default('system'); // for trader and ib system / for admin = admin, for system admin = system_admin
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
        Schema::dropIfExists('withdraws');
    }
};
