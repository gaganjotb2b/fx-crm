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
        Schema::create('symbols', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('symbol', 32);
            $table->string('title', 100);
            $table->string('ib_rebate', 32);
            $table->tinyInteger('active_status')->default(0)->comment('1 for activate, 0 for deactivate');
            $table->unsignedBigInteger('created_by');
            $table->index('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('symbols');
    }
};
