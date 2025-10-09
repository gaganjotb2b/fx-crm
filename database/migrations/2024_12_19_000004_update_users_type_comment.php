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
        Schema::table('users', function (Blueprint $table) {
            // Update the comment for the type field to include new manager types
            $table->tinyInteger('type')->nullable()->comment('0 for trader, 1 for system, 2 for admin, 3 for corporate, 4 for ib, 5 for manager, 6 for admin manager, 7 for country manager')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the comment back to original
            $table->tinyInteger('type')->nullable()->comment('0 for trader, 1 for system, 2 for admin, 3 for corporate, 4 for ib, 5 for manager')->change();
        });
    }
};
