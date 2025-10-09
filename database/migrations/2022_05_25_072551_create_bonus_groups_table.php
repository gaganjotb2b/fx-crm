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
        if (!Schema::hasTable('bonus_groups')) {
            Schema::create('bonus_groups', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('bonus_package')->comment('references by bonus package ID');
                $table->index('bonus_package');
                $table->unsignedBigInteger('group_id')->nullable()->comment('FK=>client_groups');
                $table->foreign('bonus_package')->references('id')->on('bonus_packages')->onDelete('cascade');
                $table->foreign('group_id')->references('id')->on('client_groups')->onDelete('cascade');
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
        Schema::dropIfExists('bonus_groups');
    }
};
