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
        try {
            Schema::create('custom_commissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('commission_id')->nullable()->comment('fk=>commmission structure');
                $table->index('commission_id');
                $table->json('custom_commission')->nullable()->comment('json type dta');

                $table->foreign('commission_id')->on('ib_commission_structures')->references('id')->onDelete('cascade');
                $table->timestamps();
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_commissions');
    }
};
