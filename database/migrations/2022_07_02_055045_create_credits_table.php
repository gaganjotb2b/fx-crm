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
        if (!Schema::hasTable('credits')) {
            Schema::create('credits', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('trading_account')->nullable()->comment('references on trading_accounts table');
                $table->index('trading_account');
                $table->float('amount')->default(0);
                $table->enum('type', ['add', 'deduct', 'Active'])->default('add')->comment('type amount may add, or deduct');
                $table->timestamp('expire_date');
                $table->string('transaction_id', 255);
                $table->string('note')->nullable();
                $table->unsignedBigInteger('credited_by')->nullable();
                $table->index('credited_by');
                $table->string('ip', 255)->nullable();
                $table->json('admin_log')->nullable();
                $table->string('order_number')->nullable();
                $table->timestamps();

                $table->foreign('trading_account')->references('id')->on('trading_accounts')->onDelete("cascade");
                $table->foreign('credited_by')->references('id')->on('users')->onDelete("cascade");
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
        Schema::dropIfExists('credits');
    }
};
