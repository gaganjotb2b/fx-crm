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
        if (!Schema::hasTable('online_banks')) {
            Schema::create('online_banks', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('country')->nullable();
                $table->string('currency')->nullable();
                $table->string('bank_code')->nullable();
                $table->string('bank_name')->nullable();
                $table->enum('status', ['active', 'disable', 'deleted']);
                $table->string('ip_address')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->json('admin_log')->nullable();
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
        Schema::dropIfExists('online_banks');
    }
};
