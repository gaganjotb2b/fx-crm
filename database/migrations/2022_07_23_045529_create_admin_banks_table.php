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
        if (!Schema::hasTable('admin_banks')) {
            Schema::create('admin_banks', function (Blueprint $table) {
                $table->id();
                $table->string('tab_selection', 55)->nullable();
                $table->string('tab_name', 100)->nullable();
                $table->string('bank_name', 255)->nullable();
                $table->string('account_name', 100)->nullable();
                $table->string('account_number', 255)->nullable();
                $table->string('swift_code', 255)->nullable();
                $table->string('ifsc_code', 255)->nullable();
                $table->string('routing', 255)->nullable();
                $table->string('bank_country', 255)->nullable();
                $table->string('bank_address', 255)->nullable();
                $table->unsignedBigInteger('currency_id')->nullable()->comment('reference by currency_setups table id');
                $table->index('currency_id');
                // $table->foreign('currency_id')->references('id')->on('currency_setups')->onDelete('cascade');
                $table->double('minimum_deposit')->nullable();
                $table->string('note', 255)->nullable();
                $table->boolean('status')->default(1)->comment('0 for disable, 1 for enable, 2 for delete');
                $table->json('admin_log')->nullable();
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
        Schema::dropIfExists('admin_banks');
    }
};
