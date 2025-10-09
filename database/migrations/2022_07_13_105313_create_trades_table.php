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
        if (!Schema::hasTable('trades')) {
            Schema::create('trades', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('trading_account')->nullable()->comment('references on trading_accounts table');
                $table->index('trading_account');
                $table->integer('ticket');
                $table->string('symbol')->nullable();
                $table->integer('volume')->nullable();
                $table->integer('cmd')->nullable();
                $table->double('profit')->default(0);
                $table->string('comment')->nullable();
                $table->timestamp('open_time')->nullable();
                $table->timestamp('close_time')->nullable();
                $table->string('type')->nullable()->comment('account groups');
                $table->string('status')->nullable();
                $table->string('ip', 64)->nullable();
                $table->integer('recount')->nullable();
                $table->double('open_price')->nullable();
                $table->double('close_price')->nullable();
                $table->foreign('trading_account')->references('id')->on('trading_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('trades');
    }
};
