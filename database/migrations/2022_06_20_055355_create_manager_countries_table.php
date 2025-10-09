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
        if (!Schema::hasTable('manager_countries')) {
            Schema::create('manager_countries', function (Blueprint $table) {
                $table->id();
                $table->unSignedBigInteger('manager_id')->comment('references users table');
                $table->unSignedBigInteger('accessible_country')->comment('references countries table');
                $table->index('manager_id');
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
        Schema::dropIfExists('manager_countries');
    }
};
