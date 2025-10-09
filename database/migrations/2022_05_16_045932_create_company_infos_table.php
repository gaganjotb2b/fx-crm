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
        if (!Schema::hasTable('company_infos')) {
            Schema::create('company_infos', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('name',100);
                $table->string('email',100);
                $table->string('website',100);
                $table->string('authority',100);
                $table->string('license',100);
                $table->json('phone',100);
                $table->unsignedBigInteger('agent_country');
                $table->index('agent_country');
                $table->foreign('agent_country')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::dropIfExists('company_infos');
    }
};
