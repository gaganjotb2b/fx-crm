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
        Schema::create('commission_status', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->unsignedBigInteger('ticket')->nullable();
            $table->unsignedBigInteger('login')->nullable();
            $table->timestamp('open_time')->nullable();
            $table->timestamp('close_time')->nullable();
            $table->unsignedBigInteger('ib')->nullable();
            $table->unsignedBigInteger('trader')->nullable();
            $table->text('log')->nullable();
            $table->string('status', 30)->nullable();
            $table->tinyInteger('recount')->nullable();
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
        Schema::dropIfExists('commission_status');
    }
};
