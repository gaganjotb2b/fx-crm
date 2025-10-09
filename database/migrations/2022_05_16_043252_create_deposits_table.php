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
        if (!Schema::hasTable('deposits')) {
            Schema::create('deposits', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('invoice_id', 50)->nullable();
                $table->string('account')->nullable()->comment('account id');
                $table->enum('deposit_option', ['account', 'wallet'])->default('wallet');
                $table->unsignedBigInteger('internal_transfer')->nullable()->comment('when trnsfer to account');
                $table->string('transaction_type', 25)->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('incode')->nullable();
                $table->double('amount');
                $table->double('charge')->default(0);
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->json('admin_log')->nullable();
                $table->json('client_log')->nullable();
                $table->index('approved_by');
                $table->string('order_id', 64)->nullable()->comment('order_id from api');
                $table->string('bank_proof')->nullable()->comment('like as document');
                $table->string('bank_id')->nullable()->comment('admin bank id');
                $table->unsignedBigInteger('other_transaction_id')->nullable();
                $table->index('other_transaction_id');
                $table->string('ip_address')->nullable()->comment('user ip address');
                $table->string('device_name')->nullable();
                $table->enum('approved_status', ['A', 'P', 'D'])->default('P')->comment('A for approved, P for pending, D for Decline');
                $table->string('note')->nullable();
                $table->string('currency')->nullable()->comment('local currency');
                $table->double('local_currency')->nullable()->comment('local currency rate');
                $table->timestamp('approved_date')->nullable();
                $table->enum('wallet_type', ['trader', 'ib'])->default('trader');
                $table->enum('created_by', ['system', 'admin', 'manager', 'system_admin'])->default('system');
                // foreign key
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('other_transaction_id')->references('id')->on('other_transactions')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('deposits');
    }
};
