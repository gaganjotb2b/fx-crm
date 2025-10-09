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
        if (!Schema::hasTable('vouchers')) {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->index('user_id')->nullable();
            $table->string('token',255);
            $table->double('amount');
            $table->date('expire_date');
            $table->string('security',10);
            $table->string('send_to',255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('used_by')->nullable();
            $table->string('user_type',60)->nullable()->comment('user_type as  0=Trader, 4=IB, 5=Manager: this meeans use for');
            $table->unsignedBigInteger('log_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 for inactive, 1 for active');
            $table->enum('use_status',['P','U','E'])->default('P')->comment('P for Pending, U for Used, E for Expire');
            $table->date('used_date')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('vouchers');
    }
};
