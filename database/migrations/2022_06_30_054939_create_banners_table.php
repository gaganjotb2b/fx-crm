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
        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('size')->nullable()->comment('like as 160X600');
                $table->string('banner_name')->nullable();
                $table->integer('column')->nullable();
                $table->string('use_for', 60)->nullable();
                $table->string('language', 60)->nullable();
                $table->boolean('active_status')->default(0)->comment('active_status 0 for disabled, 1 for active');
                $table->unsignedBigInteger('uploaded_by')->nullable()->comment('references users table');
                $table->index('uploaded_by');
                $table->foreign('uploaded_by')->on('users')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('banners');
    }
};
