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
        if (!Schema::hasTable('pamm_requests')) {
            Schema::create('pamm_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->index('user_id');
                $table->string('name', 100)->nullable();
                $table->string('email', 100)->nullable();
                $table->unsignedBigInteger('account')->nullable()->comment('store trading account number');
                $table->string('username', 100);
                $table->double('min_deposit')->nullable();
                $table->double('max_deposit')->nullable();
                $table->double('share_profit')->nullable();
                $table->enum('status', ['A', 'P', 'D'])->default('P');
                $table->json('admin_log')->nullable();
                $table->timestamp('approved_date')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('pamm_requests');
    }
};
