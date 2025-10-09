<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reward_country', function (Blueprint $table) {
            $table->unsignedBigInteger('reward_id');
            $table->unsignedBigInteger('country_id');

            // Foreign key relationships
            $table->foreign('reward_id')
                  ->references('id')
                  ->on('rewards')
                  ->onDelete('cascade');

            $table->foreign('country_id')
                  ->references('id')
                  ->on('countries')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reward_country');
    }
};
