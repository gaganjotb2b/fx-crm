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
        if (!Schema::hasTable('mt_serial')) {
            Schema::create('mt_serial', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->integer('login_start')->nullable();
                $table->integer('login_end')->nullable();
                $table->integer('last')->nullable();
                $table->enum('login_gen',['auto','custom','limit'])->default('auto');
                $table->enum('server',['mt4','mt5'])->default('mt4');
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
        Schema::dropIfExists('mt_serial');
    }
};
