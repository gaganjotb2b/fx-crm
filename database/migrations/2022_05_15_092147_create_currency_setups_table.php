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
        if (!Schema::hasTable('currency_setups')) {
            Schema::create('currency_setups', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('currency', 60)->nullable()->comment('currency');
                $table->double('currency_rate')->nullable();
                $table->string('transaction_type')->nullable()->comment('like as deposit/withdraw');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('ip')->nullable();
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
        Schema::dropIfExists('currency_setups');
    }
};
