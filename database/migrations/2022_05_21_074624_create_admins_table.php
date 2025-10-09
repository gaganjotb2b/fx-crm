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
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->index('user_id');

                $table->unsignedBigInteger('group_id')->nullable()->comment('References admin_gorups table');
                $table->index('group_id');

                $table->unsignedBigInteger('accessible_country')->nullable();
                $table->index('accessible_country');

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('group_id')->references('id')->on('admin_groups')->onDelete('cascade');
                $table->foreign('accessible_country')->references('id')->on('countries')->onDelete('cascade');

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
        Schema::dropIfExists('admins');
    }
};
