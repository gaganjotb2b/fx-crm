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
            Schema::create('withdraw_settings', function (Blueprint $table) {
                $table->id();
                $table->enum('withdraw_method',['bank','crypto','paypal','gcash'])->default('bank');
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
        Schema::dropIfExists('withdraw_settings');
    }
};
