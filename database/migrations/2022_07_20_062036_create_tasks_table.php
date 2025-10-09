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
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->index('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('task_name', 255);
                $table->dateTime('open_date');
                $table->dateTime('reminder_time');
                $table->string('status', 20);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->index('created_by');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->index('updated_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**c
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
