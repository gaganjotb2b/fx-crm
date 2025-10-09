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
        if (!Schema::hasTable('bonus_users')) {
            Schema::create('bonus_users', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id')->comment('FK=>users,id');
                $table->index('user_id');
                $table->unsignedBigInteger('bonus_package')->comment('references bonus_packages ID');
                $table->index('bonus_package');
                // $table->unsignedBigInteger('available_deposit');
                // available deposit calaculate by php from max deposit on bonus_packages
                $table->unsignedBigInteger('order_num')->nullable();
                $table->boolean('fill_condition')->default(0)->comment('1 for yes, 0 for not');
                $table->boolean('status')->default(0)->comment('1 for credited, 0 for not');
                $table->unsignedBigInteger('internal_transfer_id')->nullable()->comment('references by Internal Transfer Table');
                $table->index('internal_transfer_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('bonus_package')->references('id')->on('bonus_packages')->onDelete('cascade');
                $table->foreign('internal_transfer_id')->references('id')->on('internal_transfers')->onDelete('cascade');
                $table->unsignedBigInteger('account_number')->nullable()->comment('trading account number');
                $table->timestamp('credit_expire')->nullable();
                $table->double('amount', 10, 2)->default(0);
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
        Schema::dropIfExists('bonus_users');
    }
};
