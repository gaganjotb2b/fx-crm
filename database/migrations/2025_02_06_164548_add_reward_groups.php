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
        Schema::create('reward_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('reward_id');
            $table->bigInteger('group_id');

            $table->foreign('reward_id')->references('id')->on('rewards')->onDelete('cascade');
            // $table->foreign('group_id')->references('id')->on('client_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reward_groups');
    }
};
