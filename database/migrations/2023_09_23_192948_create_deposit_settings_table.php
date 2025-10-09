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
            Schema::create('deposit_settings', function (Blueprint $table) {
                $table->id();
                $table->enum('deposit_method', ['bank', 'help2pay', 'praxis', 'paypal', 'neteler', 'gcash', 'm2pay', 'crypto', 'perfect_money', 'b2b'])->default('bank');
                $table->double('min_amount')->default(0.00);
                $table->double('max_amount')->default(0.00);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->json('admin_log')->nullable();
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
        Schema::dropIfExists('deposit_settings');
    }
};
