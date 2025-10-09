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
        Schema::create('country_manager_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_manager_id');
            $table->unsignedBigInteger('manager_id');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['country_manager_id', 'manager_id'], 'unique_country_manager');
            
            // Indexes for better performance
            $table->index('country_manager_id', 'idx_country_manager');
            $table->index('manager_id', 'idx_manager');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_manager_assignments');
    }
};
