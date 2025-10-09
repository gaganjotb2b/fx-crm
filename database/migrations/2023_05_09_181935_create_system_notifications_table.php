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
        if (!Schema::hasTable('system_notifications')) {
            Schema::create('system_notifications', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();

                $table->string('notification_type')->default('system')->comment('type like as withdraw , deposit ');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->enum('user_type', ['admin', 'manager', 'system', 'ib', 'trader'])->nullable();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->enum('category', ['system', 'client'])->nullable();
                $table->string('notification')->nullable();
                $table->enum('status', ['read', 'unread', 'deleted'])->default('unread');
                $table->json('admin_log')->nullable();
                $table->string('location_url')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('table_id')->nullable();
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
        Schema::dropIfExists('system_notifications');
    }
};
