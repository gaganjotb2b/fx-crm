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
        if (!Schema::hasTable('ib_commission_structures')) {
            Schema::create('ib_commission_structures', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('symbol', 64)->nullable()->comment('Currency paire Like as URUSD');
                $table->double('total')->default(0);
                $table->string('timing')->default(0)->comment('time in minute');
                $table->unsignedBigInteger('client_group_id')->comment('group_id references client_groups table');
                $table->unsignedBigInteger('ib_group_id')->nullable()->comment('ib_group_id references ib_group tables');
                $table->unsignedBigInteger('created_by')->nullable()->comment('ib_group_id references ib_group tables');
                $table->index('client_group_id');
                $table->index('ib_group_id');
                $table->index('created_by');
                $table->json('commission')->nullable();
                $table->boolean('status')->default(0)->comment('0 for disabled, 1for enabled');
                $table->foreign('client_group_id')->on('client_groups')->references('id')->onDelete('cascade');
                $table->foreign('ib_group_id')->on('ib_groups')->references('id')->onDelete('cascade');
                $table->json('admin_log')->nullable();
                $table->foreign('created_by')->on('users')->references('id')->onDelete('cascade');

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
        Schema::dropIfExists('ib_commission_structures');
    }
};
