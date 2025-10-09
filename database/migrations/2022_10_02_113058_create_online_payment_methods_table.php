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
        if (!Schema::hasTable('online_payment_methods')) {
            Schema::create('online_payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name', 60);
                $table->json('info');
                $table->tinyInteger('status')->comment('1 for active & 0 for disable');
                $table->tinyInteger('live_demo');
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
        Schema::dropIfExists('online_payment_methods');
    }
};
