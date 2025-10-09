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
        if (!Schema::hasTable('bank_accounts')) {
            Schema::create('bank_accounts', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('bank_name', 255)->nullable();
                $table->string('bank_ac_name', 100);
                $table->string('bank_ac_number', 100);
                $table->string('bank_swift_code', 50)->nullable();
                $table->string('bank_iban', 50)->nullable();
                $table->string('bank_address', 255)->nullable();
                $table->string('bank_country', 50)->nullable();
                $table->unsignedBigInteger('currency_id')->nullable()->comment('reference by currency_setups table id');
                $table->index('currency_id');
                // $table->foreign('currency_id')->references('id')->on('currency_setups')->onDelete('cascade');
                $table->binary('note')->nullable();
                $table->string('approve_status', 50)->default('p')->comment('p->panding, a->approved, d->declined');
                $table->boolean('status')->default('0')->comment('0->deactive, 1->active, 2 = deleted');
                $table->json('admin_log')->nullable();
                $table->json('client_log')->nullable();
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
        Schema::dropIfExists('bank_accounts');
    }
};
