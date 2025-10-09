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
        if (!Schema::hasTable('theme_colors')) {
            Schema::create('theme_colors', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('primary_color', 255)->nullable();
                $table->string('body_color', 255)->nullable();
                $table->string('secondary_color', 255)->nullable();
                $table->string('form_color', 255)->nullable();
                $table->enum('use_for', ['admin_theme', 'user_theme']);
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
        Schema::dropIfExists('theme_setup');
    }
};
