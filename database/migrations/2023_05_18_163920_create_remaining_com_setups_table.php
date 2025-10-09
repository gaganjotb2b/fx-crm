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
        try {
            Schema::create('remaining_com_setups', function (Blueprint $table) {
                $table->id();
                $table->enum('remaining', ['true', 'false'])->default('false');
                $table->enum('first_level', ['percent', 'all'])->default('all');
                $table->double('amount', 10, 2)->default(0);
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remaining_com_setups');
    }
};
