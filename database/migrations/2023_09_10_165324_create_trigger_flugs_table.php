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
            Schema::create('trigger_flugs', function (Blueprint $table) {
                $table->id();
                $table->boolean('admin_bank')->default(0);
                $table->json('admin_bank_log')->nullable();
                $table->boolean('client_bank')->default(0);
                $table->boolean('deposit')->default(0);
                $table->json('deposit_log')->nullable();
                $table->boolean('withdraw')->default(0);
                $table->json('withdraw_log')->nullable();
                $table->boolean('admin')->default(0);
                $table->boolean('admin_notification')->default(0);
                $table->boolean('other_transaction')->default(0);
                $table->boolean('other_transaction_log')->default(0);
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
        Schema::dropIfExists('trigger_flugs');
    }
};
