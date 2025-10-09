<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('software_settings', function (Blueprint $table) {
            $table->enum('crm_status', ['active', 'block'])
                  ->default('active')
                  ->comment('CRM status: active or block');
        });
    }

    public function down(): void
    {
        Schema::table('software_settings', function (Blueprint $table) {
            $table->dropColumn('crm_status');
        });
    }
};
