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
        if (!Schema::hasTable('dummy_users')) {
            Schema::create('dummy_users', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('group')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('password')->nullable();
                $table->string('email_verified_at')->nullable();
                $table->string('transaction_pin')->nullable();
                $table->string('sending_mail')->nullable();
                $table->string('auto_activate')->nullable();
                $table->string('date_of_birth')->nullable();
                $table->string('gender')->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('zipcode')->nullable();
                $table->string('address')->nullable();
                $table->string('ip_address')->nullable();
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
        Schema::dropIfExists('dummy_users');
    }
};
