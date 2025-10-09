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
        if (!Schema::hasTable('com_trades')) {
            Schema::create('com_trades', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->integer('trader_id');
                $table->unsignedBigInteger('ticket')->nullable();
                $table->unsignedBigInteger('trading_account')->nullable()->comment('References trading_accounts table');
                $table->string('account_no')->nullable();
                $table->string('symbol', 64)->nullable();
                $table->integer('volume')->nullable();
                $table->double('open_price')->nullable();
                $table->double('close_price')->nullable();
                $table->integer('cmd')->nullable();
                $table->double('profit')->nullable();
                $table->string('comment')->nullable();
                $table->timestamp('open_time')->nullable();
                $table->timestamp('close_time')->nullable();
                $table->double('commission')->default(0);
                $table->integer('state')->nullable();
                $table->integer('expert_position_id')->nullable();
                $table->unsignedBigInteger('ib')->nullable();
                $table->string('type')->nullable();
                $table->string('flag')->nullable();
                $table->string('status', 64)->nullable()->comment('pending, single, comNotFound, groupIgnore, timeIgnore, credited');
                $table->integer('recount')->nullable();
                $table->integer('ib_mod')->nullable();
                $table->string('ip')->nullable();
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
        Schema::dropIfExists('com_trades');
    }
};
