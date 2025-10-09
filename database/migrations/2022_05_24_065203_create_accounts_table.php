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
        if (!Schema::hasTable('accounts')) {
            Schema::create('accounts', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('trading_account')->comment('reference from trading_account ID');
                $table->index('trading_account');
                $table->string('name',32);
                $table->string('email',100);
                $table->string('phone',20)->nullable();
                $table->string('password',255);
                $table->boolean('account_status')->comment('0 for disabled, 1 for active');
                
                $table->foreign('trading_account')->references('id')->on('trading_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('accounts');
    }
};
