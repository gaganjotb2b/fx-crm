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

        if (!Schema::hasTable('old_balances')) {
            Schema::create('old_balances', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->double('balance')->default(0.00);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('old_balances');
    }
};
