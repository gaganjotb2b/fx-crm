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
        if (!Schema::hasTable('ticket_reply')) {
            Schema::create('ticket_reply', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('ticket_id')->nullable();
                $table->index('ticket_id');
                $table->text('reply_description')->nullable();
                $table->unsignedBigInteger('replay_by')->nullable();
                $table->unsignedBigInteger('attch_id')->nullable();
                $table->index('attch_id');
                $table->index('replay_by');
                $table->foreign('attch_id')->references('id')->on('ticket_attachment')->onDelete('cascade');
                $table->foreign('replay_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');

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
        Schema::dropIfExists('ticket_reply');
    }
};
