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
        if (!Schema::hasTable('theme_setups')) {
            Schema::create('theme_setups', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->enum('theme_name', ['metronic', 'soft-ui','vieuxy'])->default('soft-ui');
                $table->enum('theme_version', ['light-layout', 'dark-layout', 'semi-dark-layout', 'bordered-layout'])->default('light-layout');
                $table->enum('use_for', ['admin', 'client'])->default('admin');
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
        Schema::dropIfExists('theme_setups');
    }
};
