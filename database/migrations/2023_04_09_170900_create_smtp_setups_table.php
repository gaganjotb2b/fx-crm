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
        if (!Schema::hasTable('smtp_setups')) {
            Schema::create('smtp_setups', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('mail_driver')->nullable();
                $table->string('host')->nullable();
                $table->string('port')->nullable();
                $table->string('mail_user')->nullable();
                $table->string('mail_password')->nullable();
                $table->string('mail_encryption')->nullable();
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
        Schema::dropIfExists('smtp_setups');
    }
};
