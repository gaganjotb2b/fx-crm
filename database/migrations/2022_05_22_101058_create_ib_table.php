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
        if (!Schema::hasTable('ib')) {
            Schema::create('ib', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('ib_id')->comment('user id as ib_id, if user refer other user');
                $table->index('ib_id');
                $table->unsignedBigInteger('reference_id')->comment('user id as reference_id, if user references by other user');
                $table->index('reference_id');
                $table->unsignedBigInteger('ib_group_id')->nullable()->comment('ib_group id as ib_group_id');
                $table->index('ib_group_id');
                $table->foreign('ib_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('reference_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('ib');
    }
};
