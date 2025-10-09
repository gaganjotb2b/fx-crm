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
        if (!Schema::hasTable('trading_accounts')) {
            Schema::create('trading_accounts', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->index('user_id');
                $table->unsignedBigInteger('account_number')->nullable()->comment('store ccount number from API');
                $table->boolean('account_status')->default(1)->comment('0 for hidden, 1 for visible to trader');
                $table->string('platform', 60)->default('MT4');
                $table->unsignedBigInteger('group_id')->comment('references client group table');
                $table->index('group_id');
                $table->unsignedBigInteger('leverage');
                $table->string('base_currency', 32)->nullable();
                $table->string('client_type', 64)->nullable();
                $table->string('phone_password', 60)->nullable();
                $table->string('master_password', 60)->nullable();
                $table->string('investor_password', 60)->nullable();
                $table->float('balance')->default(0);
                $table->string('comment')->nullable();
                $table->boolean('block_status')->default(1)->comment('0 for block, 1 for unblock');
                $table->boolean('commission_status')->default(1)->comment('0 for disabled, 1 for active');
                $table->boolean('deposit_status')->default(1)->comment('0 for disabled, 1 for active');
                $table->boolean('withdraw_status')->default(1)->comment('0 for disabled, 1 for active');
                $table->string('user_name')->nullable()->comment('use only vertexFX');
                $table->string('client_id')->nullable()->comment('use only vertexFX');

                $table->boolean('approve_status')->default(1)->comment('0 for pending, 1 for active, 2 for block');
                $table->timestamp('approve_date')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();

                $table->unsignedBigInteger('page')->default(0); //this column need for get mt5 trades

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('group_id')->references('id')->on('client_groups')->onDelete('cascade');
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
        Schema::dropIfExists('trading_accounts');
    }
};
