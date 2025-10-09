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
        if (!Schema::hasTable('internal_transfers')) {
            Schema::create('internal_transfers', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->comment('User ID as trader "ID"');
                $table->string('platform', 100)->nullable();
                $table->unsignedBigInteger('account_id')->nullable()->comment('account ID as trading_account "ID"');
                $table->index('user_id');
                $table->index('account_id');
                $table->string('invoice_code');
                $table->double('amount')->default(0.00);
                $table->double('charge')->default(0.00);
                $table->string('order_id')->nullable();
                $table->string('type', 50)->nullable()->comment('Type as Transaction type like, atw, wta');
                $table->enum('status', ['A', 'P', 'D'])->default('P')->comment('A for Approved, P for Pending, D for decline');
                $table->string('note')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->index('approved_by');
                $table->timestamp('approved_date')->nullable();
                $table->json('admin_log')->nullable();
                $table->json('client_log')->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('account_id')->references('id')->on('trading_accounts')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('internal_transfers');
    }
};
