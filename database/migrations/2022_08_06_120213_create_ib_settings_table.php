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
        if (!Schema::hasTable('ib_settings')) {
            Schema::create('ib_settings', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('settings', 255);
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('system_disable')->default(0)->comment('fill by system admin');
                $table->boolean('status')->default(0)->comment('0 for disable, 1 for enable');
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
        Schema::dropIfExists('ib_settings');
    }
};
