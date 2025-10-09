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
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->index('user_id');
                $table->enum('user_type', ['trader', 'ib'])->default('trader');
                $table->string('subject')->nullable();
                $table->text('description')->nullable();
                $table->enum('priority', ['normal', 'high', 'critical'])->default('normal');
                $table->enum('fa', ['0', '1'])->default('0');
                $table->integer('asign_to')->nullable();
                $table->string('comment')->nullable();
                $table->unsignedBigInteger('attch_id')->nullable();
                $table->index('attch_id');
                $table->foreign('attch_id')->references('id')->on('ticket_attachment')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->enum('status', ['Open', 'Closed', 'Answered', 'In-Progress', 'On-Hold'])->default('On-Hold');
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
        Schema::dropIfExists('tickets');
    }
};
