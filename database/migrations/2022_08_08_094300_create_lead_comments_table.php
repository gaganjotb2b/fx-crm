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


        if (!Schema::hasTable('lead_comments')) {
            Schema::create('lead_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->index('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('client_id_number', 100);
                $table->string('user_type', 100);
                $table->string('note', 255);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->index('created_by');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->index('updated_by');
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
        Schema::dropIfExists('lead_comments');
    }
};
