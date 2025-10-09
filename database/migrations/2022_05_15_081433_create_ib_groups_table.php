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
        if (!Schema::hasTable('ib_groups')) {
            Schema::create('ib_groups', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('group_name', 50);
                $table->tinyInteger('status')->default(1)->comment('0->deactivate, 1->active');
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
        Schema::dropIfExists('ib_groups');
    }
};
