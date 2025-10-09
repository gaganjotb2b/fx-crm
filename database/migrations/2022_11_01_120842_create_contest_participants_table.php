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
        Schema::create('contest_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contest_package_id')->nullable();
            $table->string('platform',30)->nullable();
            $table->string('trading_ac',100)->nullable();
            $table->string('user_type',100)->nullable();
            $table->enum('status',['active','inactive','cancel','won']);
            $table->index('contest_package_id');
            $table->foreign('contest_package_id')->references('id')->on('contests')->onDelete('cascade');
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
        Schema::dropIfExists('contest_participants');
    }
};
