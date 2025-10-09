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
        if (!Schema::hasTable('required_fields')) {
            Schema::create('required_fields', function (Blueprint $table) {
                $table->id();
                $table->boolean('phone')->default(1);
                $table->boolean('gender')->default(1);
                $table->boolean('password')->default(1);
                $table->boolean('country')->default(1);
                $table->boolean('state')->default(1);
                $table->boolean('city')->default(1);
                $table->boolean('zip_code')->default(1);
                $table->boolean('address')->default(1);
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
        Schema::dropIfExists('required_fields');
    }
};
