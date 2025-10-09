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
        try {
            Schema::create('api_configs', function (Blueprint $table) {
                // $table->engine = "InnoDB";
                $table->id();

                // api configuration
                $table->string('demo_api_key')->nullable();
                $table->string('live_api_key')->nullable();

                $table->string('server_ip')->nullable();
                $table->string('manager_login')->nullable();
                $table->string('server_port')->nullable();
                $table->string('web_password')->nullable();
                $table->string('manager_password')->nullable();

                $table->string('api_key')->nullable();
                $table->string('api_url')->nullable();
                $table->enum('platform_type', ['mt4', 'mt5'])->default('mt5');
                $table->string('server_type')->nullable();
                $table->string('status')->nullable();

                $table->timestamps();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_configs');
    }
};
