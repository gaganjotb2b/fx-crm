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
        if (!Schema::hasTable('ib_incomes')) {
            Schema::create('ib_incomes', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('ib_id')->nullable()->comment('references on users table');
                $table->unsignedBigInteger('trader_id')->nullable()->comment('references on users table');
                $table->index('ib_id');
                $table->string('order_num')->nullable()->comment('data from api');
                $table->unsignedBigInteger('trading_account')->nullable()->comment('references on trading_accounts table');
                $table->index('trading_account');
                $table->string('symbol')->nullable();
                $table->integer('cmd')->nullable();
                $table->integer('volume')->nullable();
                $table->double('profit')->nullable();
                $table->timestamp('open_time')->nullable();
                $table->timestamp('close_time')->nullable();
                $table->string('comment')->nullable();
                $table->decimal('amount')->default(0);
                $table->integer('com_level')->default(0);
                $table->double('level_com')->default(0);
                $table->integer('total_ibs')->nullable();
                $table->string('account_group')->nullable();
                $table->string('ip', 64)->nullable();

                // $table->foreign('trading_account')->references('account_number')->on('trading_accounts')->onDelete('cascade');
                // $table->foreign('ib_id')->references('id')->on('users')->onDelete('cascade');
                // $table->foreign('trader_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('ib_incomes');
    }
};
