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
        if (!Schema::hasTable('finance_ops')) {
            Schema::create('finance_ops', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->index('user_id')->nullable();

                $table->boolean('deposit_operation')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('withdraw_operation')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('internal_transfer')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('wta_transfer')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('trader_to_trader')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('trader_to_ib')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('ib_to_ib')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('ib_to_trader')->default(1)->comment('0 for false, 1 for true');
                $table->boolean('kyc_verify')->default(1)->comment('0 for false, 1 for true');

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
        Schema::dropIfExists('finance_ops');
    }
};
