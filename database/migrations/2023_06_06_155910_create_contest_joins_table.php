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
            Schema::create('contest_joins', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->index('user_id');
                $table->unsignedBigInteger('contest_id')->nullable();
                $table->string('account_number')->nullable();
                $table->index('contest_id');
                $table->double('total_profit', 10, 2)->default(0.00);
                $table->double('total_lot', 10, 2)->default(0.00);
                $table->unsignedBigInteger('position')->default(0);
                $table->foreign('contest_id')->on('contests')->references('id')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('contest_joins');
    }
};
