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
        if (!Schema::hasTable('manager_users')) {
            Schema::create('manager_users', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->comment('references users table');
                $table->index('user_id');
                
                $table->unsignedBigInteger('manager_id')->nullable()->comment('references users table');
                $table->index('manager_id');

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('manager_users');
    }
};
