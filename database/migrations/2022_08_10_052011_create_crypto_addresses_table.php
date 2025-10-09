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
        if (!Schema::hasTable('crypto_addresses')) {
        Schema::create('crypto_addresses', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('address',255)->nullable();
            $table->string('name',255)->nullable();
            $table->string('block_chain',100)->nullable();
            $table->string('created_by',100)->nullable()->comment('created by email');
            $table->string('created_ip',100)->nullable()->comment('created IP');
            $table->string('browser',255)->nullable();
            $table->string('country',100)->nullable();
            $table->tinyInteger('verify_1')->default('0');
            $table->tinyInteger('verify_2')->default('0');
            $table->timestamp('verify_1_at')->nullable();
            $table->timestamp('verify_2_at')->nullable();
            $table->integer('c_count')->default('0');
            $table->integer('c_update')->default('0');
            $table->string('verify_1_ip',100)->nullable();
            $table->string('verify_2_ip',100)->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->index('admin_id');
            $table->tinyInteger('status')->default('0');
            $table->string('token',255)->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('crypto_addresses');
    }
};
