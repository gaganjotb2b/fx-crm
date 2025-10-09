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
        if (!Schema::hasTable('payment_status')) {
            Schema::create('payment_status', function (Blueprint $table) {
                $table->id();
                $table->string('payment_id', 255);
                $table->string('txid', 255);
                $table->double('amount');
                $table->tinyInteger('status')->comment('0 => pending, 1 => paid');
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
        Schema::dropIfExists('payment_status');
    }
};
