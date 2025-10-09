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
        if (!Schema::hasTable('mobile_app_settings')) {
            Schema::create('mobile_app_settings', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('logo_loader')->nullable()->comment('app preloader');
                $table->string('logo_brand')->nullable()->comment('logo for app brand');
                $table->string('theme')->default('v2')->comment('v2,v1');
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
        Schema::dropIfExists('mobile_app_settings');
    }
};
