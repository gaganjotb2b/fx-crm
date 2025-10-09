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
        if (!Schema::hasTable('button_actions')) {
            Schema::create('button_actions', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('submit_wait')->nullable()->comment('time in second');
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
        Schema::dropIfExists('button_actions');
    }
};
