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
        if (!Schema::hasTable('user_descriptions')) {
            Schema::create('user_descriptions', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('country_id')->nullable();
                $table->index('user_id')->nullable();
                $table->index('country_id')->nullable();
                $table->string('state', 60)->nullable();
                $table->string('city', 60)->nullable();
                $table->string('address', 60)->nullable();
                $table->string('zip_code', 20)->nullable();
                $table->enum('gender', ['male','female','other'])->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string("profile_avater")->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');

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
        Schema::dropIfExists('user_descriptions');
    }
};
