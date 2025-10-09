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
        if (!Schema::hasTable('footer_links')) {
            Schema::create('footer_links', function (Blueprint $table) {
                $table->id();
                $table->string('aml_policy', 255)->nullable();
                $table->string('contact_us', 255)->nullable();
                $table->string('privacy_policy', 255)->nullable();
                $table->string('refund_policy', 255)->nullable();
                $table->string('terms_and_cond', 255)->nullable();
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
        Schema::dropIfExists('footer_links');
    }
};
