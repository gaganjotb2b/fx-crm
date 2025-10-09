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
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->double('withdraw_limit', 10, 2)->nullable();
            $table->enum('kyc', [0, 1])->default(0)->comment('0=>not required, 1=>required');
            $table->string('user_type', 20)->nullable()->comment('Trader,IB');
            $table->string('contest_name', 255)->nullable()->comment('contest title');
            $table->enum('credit_type', ['fixed', 'percent'])->default('fixed');
            $table->double('contest_amount', 10, 2)->nullable();
            $table->integer('expire_after');
            $table->enum('expire_type', ['days', 'months', 'years'])->default('days');
            $table->enum('contest_type', ['on_profit', 'on_profit_ratio', 'on_lot'])->default('on_profit');
            $table->enum('allowed_for', ['all_clients', 'new_registration', 'new_accounts'])->default('all_clients');
            $table->enum('is_global', ['0', '1'])->default(1);
            $table->integer('max_contest')->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('client_limit')->nullable();
            $table->integer('min_join')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->json('contest_prices');
            $table->unsignedBigInteger('client_group')->nullable();
            $table->unsignedBigInteger('ib_group')->nullable();
            $table->string('popup_image')->nullable();
            $table->enum('status', ['active', 'disable', 'closed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contests');
    }
};
