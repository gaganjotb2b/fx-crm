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
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('name', 100);
                $table->string('client_type', 20)->comment('demo, trader, ib');
                $table->tinyInteger('priority')->nullable()->comment('1->normal, 2->important, 3->very important');
                $table->unsignedBigInteger('created_by')->nullable()->comment('refences users table');
                $table->unsignedBigInteger('updated_by')->nullable()->comment('references users table');
                $table->tinyInteger('status')->nullable()->comment('0, 1');

                // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                // $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('categories');
    }
};
