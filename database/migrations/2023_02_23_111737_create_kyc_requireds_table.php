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
        if (!Schema::hasTable('kyc_requireds')) {
            Schema::create('kyc_requireds', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->boolean('deposit')->default(0)->comment('1= kyc required for deposit, 0 for not required');
                $table->boolean('withdraw')->default(0)->comment('1= kyc required for withdraw, 0 for not required');
                $table->boolean('open_account')->default(0)->comment('1= kyc required for open_account, 0 for not required');
                $table->boolean('system_disable')->default(0)->comment('1= for disabled by system, 0 for disabled by admin');
                $table->unsignedBigInteger('created_by')->default(1);
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
        Schema::dropIfExists('kyc_requireds');
    }
};
