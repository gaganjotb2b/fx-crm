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
        if (!Schema::hasTable('external_fund_transfers')) {
            Schema::create('external_fund_transfers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sender_id')->nullable()->comment('sender ID as user "ID"');
                $table->unsignedBigInteger('receiver_id')->nullable()->comment('receiver ID as user "ID"');
                $table->double('amount')->default(0.00);
                $table->double('charge')->default(0.00);
                $table->enum('type', ['ib_to_trader', 'trader_to_trader', 'ib_to_ib', 'trader_to_ib'])->nullable()->comment('Transaction type like, trader to trader');
                $table->enum('status', ['A', 'P', 'D'])->default('P')->comment('A for Approved, P for Pending, D for decline');
                $table->string('note')->nullable()->comment('Note by admin why disable');
                $table->string('txnid')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->index('approved_by');
                $table->json('admin_log')->nullable();
                $table->timestamp('approved_date')->nullable();
                $table->index('sender_id');
                $table->index('receiver_id');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
                $table->enum('sender_wallet_type', ['trader', 'ib'])->default('trader');
                $table->enum('receiver_wallet_type', ['trader', 'ib'])->default('trader');
                $table->string('ip_address')->nullable()->comment('sender ip');
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
        Schema::dropIfExists('external_fund_transfers');
    }
};
