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
        if (!Schema::hasTable('lead_management')) {
            Schema::create('lead_management', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('name', 255);
                $table->string('email', 255);
                $table->string('phone', 20);
                $table->string('country', 255);
                $table->string('state', 255);
                $table->string('city', 255);
                $table->string('zip', 255);
                $table->string('account', 100);
                $table->string('have_task', 30);
                $table->unsignedBigInteger('manager_id')->nullable()->comment('FK=>manager_id, (Users)');
                $table->unsignedBigInteger('category_id')->nullable()->comment('categories table');
                $table->index('category_id');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->index('created_by');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->index('updated_by');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('lead_management');
    }
};
