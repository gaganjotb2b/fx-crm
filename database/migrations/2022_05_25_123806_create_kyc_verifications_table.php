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
        if (!Schema::hasTable('kyc_verifications')) {
            Schema::create('kyc_verifications', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->index('user_id');
                $table->string('id_number', 64)->nullable()->comment('like as NID');
                $table->timestamp('issue_date')->nullable();
                $table->string('exp_date')->nullable();
                $table->string('doc_type', 32)->nullable()->comment('like as password, NID');
                $table->string('perpose', 64)->nullable()->comment('like as "ID proof"');
                $table->json('document_name')->nullable()->comment('Front and/or back side of document');
                $table->boolean('status')->default(0)->comment('Approve status 0 for pending, 1 for verified, 2 for declined');
                $table->string('note')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable()->comment('Whose admin approved kyc');
                $table->json('admin_log')->nullable();
                $table->index('approved_by');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
                $table->timestamp('approved_date')->nullable();
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
        Schema::dropIfExists('kyc_verifications');
    }
};
