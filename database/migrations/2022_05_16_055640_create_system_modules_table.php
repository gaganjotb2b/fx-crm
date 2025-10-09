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
        if (!Schema::hasTable('system_modules')) {
            Schema::create('system_modules', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('module',100);
                $table->string('module_description',100)->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('system_disable')->nullable();
                $table->boolean('status')->default(1);
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
        Schema::dropIfExists('system_modules');
    }
};
