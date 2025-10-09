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
        if (!Schema::hasTable('software_settings')) {
            Schema::create('software_settings', function (Blueprint $table) {
                $table->id();
                $table->engine = "InnoDB";
                $table->string('email_template')->default('v2')->comment('Email template version like as v1, v2');
                $table->boolean('account_move')->default('0')->comment('0=> admin can not move account, 1=> admin can move account');
                $table->boolean('is_multicurrency')->default(0)->comment('0=>single currency, 1=>multiple currency');
                $table->boolean('auto_c_rate')->default(0)->comment('0=>dynamic currency rate, 1=>automatic currency rate');
                $table->boolean('is_single_portal')->default(1)->comment('this filds for combine crm 0=>single portal, 1=>multiple dual portal');
                
                $table->json('admin_log')->nullable();
                $table->enum('version', ['pro', 'lite'])->default('pro')->comment('pro, lite');
                $table->enum('direct_deposit', ['account', 'wallet'])->default('wallet');
                $table->enum('direct_withdraw', ['account', 'wallet'])->default('wallet');
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
        Schema::dropIfExists('software_settings');
    }
};
