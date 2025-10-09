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
        Schema::create('copy_symbols', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20);
            $table->string('symbol_org', 20);
            $table->string('title', 20);
            $table->decimal('comm');
            $table->enum('ib_rebate', ['YES', 'NO']);
            $table->string('group_name', 50);
            $table->integer('group_id');
            $table->string('added_by', 100);
            $table->enum('visible', ['visible', 'hidden']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copy_symobls');
    }
};
