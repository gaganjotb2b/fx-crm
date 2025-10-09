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
        if (!Schema::hasTable('mt5_trades')) {
            Schema::create('mt5_trades', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->bigInteger('TICKET')->primary();
                $table->integer('LOGIN');
                $table->string('SYMBOL');
                $table->integer('DIGITS');
                $table->integer('CMD');
                $table->integer('VOLUME');
                $table->dateTime('OPEN_TIME');
                $table->double('OPEN_PRICE');
                $table->double('SL');
                $table->double('TP');
                $table->dateTime('CLOSE_TIME');
                $table->integer('EXPIRATION')->default(0);
                $table->integer('REASON')->default(0);
                $table->bigInteger('DEAL')->default(0);
                $table->double('CONV_RATE1');
                $table->double('CONV_RATE2');
                $table->double('COMMISSION');
                $table->double('COMMISSION_AGENT');
                $table->double('SWAPS');
                $table->double('CLOSE_PRICE');
                $table->double('PROFIT');
                $table->double('TAXES');
                $table->string('COMMENT')->nullable();
                $table->integer('INTERNAL_ID');
                $table->double('MARGIN_RATE');
                $table->integer('TIMESTAMP');
                $table->integer('MAGIC')->default(0);
                $table->integer('GW_VOLUME')->default(0);
                $table->integer('GW_OPEN_PRICE')->default(0);
                $table->integer('GW_CLOSE_PRICE')->default(0);
                $table->dateTime('MODIFY_TIME');
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
        Schema::dropIfExists('mt5_trades');
    }
};
