<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tour_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tour_name');
            $table->string('organization_name')->nullable();
            $table->unsignedBigInteger('client_group_id')->nullable();
            $table->decimal('min_deposit', 15, 2)->nullable();
            $table->integer('group_trading_duration')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('prize_1')->nullable();
            $table->string('prize_2')->nullable();
            $table->string('prize_3')->nullable();
            $table->string('prize_4')->nullable();
            $table->integer('registration_period')->nullable();
            $table->timestamps(); // creates created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_settings');
    }
};
