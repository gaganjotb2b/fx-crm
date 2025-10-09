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
        if (!Schema::hasTable('pamm_settings')) {
            Schema::create('pamm_settings', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->increments('id');
                $table->integer('global_pamm_status');
                $table->integer('profit_share_status');
                $table->integer('flexible_profit_share_status');
                $table->integer('profit_share_commission');
                $table->integer('manual_approve_pamm_reg');
                $table->integer('pamm_requirement_status');
                $table->integer('profit_share_commission_status');
                $table->string('pamm_requirement');
                $table->integer('profit_share_value');
                $table->string('pamm_global_deposit');
                $table->integer('master_limit');
                $table->integer('slave_limit');
                $table->integer('pamm_account_limit');
                $table->integer('minimum_deposit');
                $table->integer('minimum_wallet_balance');
                $table->integer('minimum_account_balance');
                $table->integer('minimum_profit_share_value');
                $table->integer('maximum_profit_share_value');
                $table->integer('profit_share_commission_value');
                $table->integer('profit_share_margin_value');
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
        Schema::dropIfExists('pamm_settings');
    }
};
