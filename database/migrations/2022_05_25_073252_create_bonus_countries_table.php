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
        if (!Schema::hasTable('bonus_countries')) {
            Schema::create('bonus_countries', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('bonus_package')->comment('references bonus_packages ID');
                $table->index('bonus_package');
                $table->unsignedBigInteger('country')->comment('references coutries ID');
                $table->index('country');
                $table->foreign('bonus_package')->references('id')->on('bonus_packages')->onDelete('cascade');
                $table->foreign('country')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::dropIfExists('bonus_countries');
    }
};
