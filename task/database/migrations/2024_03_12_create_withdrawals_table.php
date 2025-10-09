<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->bigInteger('manager_id');
            $table->foreignId('country_id')->constrained();
            $table->decimal('amount', 15, 2);
            $table->decimal('usdt_rate', 15, 2);
            $table->decimal('amount_in_usdt', 15, 2);
            $table->string('account_number');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
}; 