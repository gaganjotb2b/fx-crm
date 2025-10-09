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
        if (!Schema::hasTable('bonus_packages')) {
            Schema::create('bonus_packages', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('pkg_name', 64);
                $table->enum('credit_type', ['fixed', 'percent'])->comment('credited by percent or fixed');
                $table->enum('bonus_type', ['free', 'on_deposit', 'first_deposit', 'specific_deposit'])->default('free')->comment('bonus type on-deposit, free,first-deposit');
                $table->float('bonus_amount', 10, 2)->default(0)->comment('if credit_type is fixed, data store here');
                $table->float('bonus_percent', 10, 2)->default(0)->comment('if credit_type is percent, data store here');
                $table->string('bonus_currency', 32)->nullable();
                $table->double('max_lot')->nullable();
                $table->double('max_withdraw')->nullable();
                $table->double('max_deposit')->nullable();
                $table->double('min_deposit')->nullable();
                $table->double('max_bonus')->nullable();
                $table->enum('bonus_for', ['all', 'new_registration', 'new_account'])->default('all')->comment('if individual data store on bonus_for table');
                $table->boolean('active_status')->default(1)->comment('0 for disable or stop, 1 for active');
                $table->boolean('is_global');
                $table->unsignedBigInteger('created_by')->comment('whose create the bonus');
                $table->index('created_by');
                $table->timestamp('start_date')->nullable()->comment('when start the bonus');
                $table->timestamp('end_date')->nullable()->comment('when end the bonus');
                $table->integer('expire_after')->nullable()->comment('Like as 10 day,must be integer days');
                $table->enum('expire_type', ['days', 'months', 'years'])->nullable()->comment('days, months years');
                $table->double('min_lot', 10, 2)->nullable()->comment('minimum lot required for withdraw');
                $table->enum('bonus_on', ['new_account', 'deposit', 'new_registration'])->default('deposit');
                $table->timestamp('expire_at')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('bonus_packages');
    }
};
