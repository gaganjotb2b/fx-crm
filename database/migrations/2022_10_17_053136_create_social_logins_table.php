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
        if (!Schema::hasTable('social_logins')) {
            Schema::create('social_logins', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->boolean('facebook')->default(1);
                $table->boolean('google')->default(1);
                $table->boolean('mac')->default(1);
                $table->unsignedBigInteger('admin_id')->nullable()->comment('FK:Users(id)');
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
        Schema::dropIfExists('social_logins');
    }
};
