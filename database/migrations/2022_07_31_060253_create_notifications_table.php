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
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('type', 32)->nullable()->comment('notification for like as deposit, withdraw');
                $table->string('description', 255)->nullable()->comment('mail subject');
                $table->longText('notification_body')->nullable()->comment('main message');
                $table->longText('notification_header')->nullable();
                $table->longText('notification_footer')->nullable();
                $table->string('email', 255)->nullable();
                $table->boolean('status')->default(0)->comment('0 for disable, 1 for enable');
                $table->string('user_type', 32);
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
        Schema::dropIfExists('notifications');
    }
};
