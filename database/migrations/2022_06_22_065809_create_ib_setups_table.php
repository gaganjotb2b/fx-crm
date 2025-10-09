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
        if (!Schema::hasTable('ib_setups')) {
            Schema::create('ib_setups', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->integer('ib_level')->nullable();
                $table->json('colors')->nullable();
                $table->integer('require_sub_ib')->nullable();
                $table->integer('min_withdraw')->nullable();
                $table->integer('max_withdraw')->nullable();
                $table->string('withdraw_period')->nullable()->comment('like as monthly, daily');
                $table->string('period_days', 60)->nullable()->comment('if period is weekly, days like friday');
                $table->string('period_date', 60)->nullable()->comment('if period is monthly, date like 01,02');
                $table->string('byweekly_period_date', 60)->nullable()->comment('if period is monthly, date like 01,02');
                $table->boolean('withdraw_kyc')->default(0);
                $table->boolean('refer_kyc')->default(0)->comment('kyc for refarral');
                $table->boolean('ib_commission_kyc')->default(0);

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
        Schema::dropIfExists('ib_setups');
    }
};
