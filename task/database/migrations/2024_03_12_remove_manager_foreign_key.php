<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deposits', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['manager_id']);
            
            // Change the manager_id column to be just a regular bigInteger
            $table->bigInteger('manager_id')->change();
        });
    }

    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            // Re-add the foreign key constraint if we need to roll back
            $table->foreign('manager_id')->references('id')->on('users');
        });
    }
}; 