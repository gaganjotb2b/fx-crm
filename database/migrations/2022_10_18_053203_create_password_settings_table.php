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
        if (!Schema::hasTable('password_settings')) {
            Schema::create('password_settings', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->boolean('master_password')->default(1);
                $table->boolean('investor_password')->default(1);
                $table->boolean('leverage')->default(1);
                $table->unsignedBigInteger('admin_id')->nullable()->comment('FK:Users(id)');
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
        Schema::dropIfExists('password_settings');
    }
};
