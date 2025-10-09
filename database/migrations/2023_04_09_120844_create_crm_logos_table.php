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
        if (!Schema::hasTable('crm_logos')) {
            Schema::create('crm_logos', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('dark_layout')->nullable()->comment('logo for dark layout');
                $table->string('light_layout')->nullable()->comment('logo for light layout');
                $table->string('email_logo')->nullable()->comment('logo for email template');
                $table->string('fevicon')->nullable()->comment('logo for fevicon');
                $table->enum('ratio', ['rectangular', 'square'])->default('rectangular');
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
        Schema::dropIfExists('crm_logos');
    }
};
