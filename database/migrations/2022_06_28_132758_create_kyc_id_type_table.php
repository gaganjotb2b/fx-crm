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
        if (!Schema::hasTable('kyc_id_type')) {
            Schema::create('kyc_id_type', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('id_type')->nullable()->comment('like as password, driveing license');
                $table->enum('group', ['id proof', 'address proof'])->default('id proof')->comment('like as "id proof","address proof"');
                $table->unsignedBigInteger('created_by')->comment('account reference by users table');
                $table->index('created_by');
                $table->boolean('has_issue_date')->default(1);
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('kyc_id_type');
    }
};
