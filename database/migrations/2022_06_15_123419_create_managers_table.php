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
        if (!Schema::hasTable('managers')) {
            Schema::create('managers', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->index('user_id');
                $table->unsignedBigInteger('group_id');
                $table->index('group_id');
                $table->integer('priority');
                $table->boolean('is_mailable')->default(0)->comment('1 for true/sending mail, 0 for false/mail not sending');
                $table->integer('monthly_limit')->nullable();
                $table->integer('daily_limit')->nullable();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('group_id')->references('id')->on('manager_groups')->onDelete('cascade');
                
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
        Schema::dropIfExists('managers');
    }
};
