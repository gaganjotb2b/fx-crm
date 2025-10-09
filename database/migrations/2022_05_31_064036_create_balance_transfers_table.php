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
        if (!Schema::hasTable('balance_transfers')) {
            Schema::create('balance_transfers', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->unsignedBigInteger('sender_id')->nullable()->comment('sender ID as user "ID"');
                $table->unsignedBigInteger('receiver_id')->nullable()->comment('receiver ID as user "ID"');
                $table->double('amount')->default(0.00);
                $table->enum('status', ['A', 'P','D'])->default('P')->comment('A for Approved, P for Pending, D for decline');
                $table->string('txn_ID')->nullable();
                $table->index('sender_id');
                $table->index('receiver_id');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('balance_transfers');
    }
};
