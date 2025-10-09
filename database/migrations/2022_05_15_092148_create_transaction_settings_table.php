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

    // if (!Schema::hasTable('system_configs')) {
    //     Schema::create('system_configs', function (Blueprint $table) {
    public function up()
    {
        if (!Schema::hasTable('transaction_settings')) {
            Schema::create('transaction_settings', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('transaction_type', 32);
                $table->double('min_transaction', 10, 2)->nullable;
                $table->double('max_transaction', 10, 2)->nullable;
                $table->string('charge_type', 32);
                $table->double('limit_start', 10, 2)->nullable;
                $table->double('limit_end', 10, 2)->nullable;
                $table->boolean('kyc')->default(0)->comment('1 for true, 0 for false');
                $table->double('amount', 10, 2)->unsigned()->default(0);
                $table->string('permission', 32)->default('Panding');
                $table->boolean('active_status')->default('0')->comment('0 for disable, 1 for active');
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
        Schema::dropIfExists('transaction_settings');
    }
};
