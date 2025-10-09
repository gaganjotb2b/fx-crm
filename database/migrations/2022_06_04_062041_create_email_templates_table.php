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
        if (!Schema::hasTable('email_templates')) {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('name',100);
                $table->string('use_for',30);
                $table->enum('status', ['se', 'pe','me'])->default('se')->comment('se for system email, pe for promotion email, me for marketing email');
                $table->unsignedBigInteger('created_by')->nullable()->comment('Whose created the template');
                $table->index('created_by');
                $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('email_templates');
    }
};
