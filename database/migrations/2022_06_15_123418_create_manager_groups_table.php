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
        if (!Schema::hasTable('manager_groups')) {
            Schema::create('manager_groups', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('group_type', 32)->comment('1 for custom, 0 for desk');
                $table->string('group_name')->comment('account manager');
                $table->unsignedBigInteger('created_by')->comment('references by user');
                $table->index('created_by');
                $table->boolean('active_status')->default(1)->comment('0 for disabled, 1 for acitve');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('manager_groups');
    }
};
