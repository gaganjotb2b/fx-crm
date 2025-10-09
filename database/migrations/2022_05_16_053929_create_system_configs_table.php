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
        if (!Schema::hasTable('system_configs')) {
            Schema::create('system_configs', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                // theme setup
                $table->string('platform_type', 32)->nullable();
                $table->json('server_type')->nullable();
                $table->json('platform_download_link')->nullable();

                // company setup
                $table->string('com_name', 60)->nullable();
                $table->string('com_license', 60)->nullable();
                $table->json('com_email')->nullable();
                $table->json('com_phone')->nullable();
                $table->string('com_website', 60)->nullable();
                $table->string('com_authority', 60)->nullable();
                $table->string('com_address', 255)->nullable();
                $table->string('copyright', 60)->nullable();
                $table->string('support_email', 60)->nullable();
                $table->string('auto_email', 60)->nullable();
                $table->json('com_social_info')->nullable();

                // privacy statement
                $table->text('privacy_statement')->nullable();

                // software settings
                $table->enum('crm_type', ['default', 'combined'])->default('default');
                $table->tinyInteger('create_meta_acc')->default(0)->comment('0 for manually, 1 for automatically');
                $table->string('platform_book', 32)->nullable();
                $table->integer('acc_limit')->nullable()->comment('trading account limit');
                $table->tinyInteger('brute_force_attack')->default(0)->comment('1 for activate, 0 for deactivate');
                $table->boolean('social_account')->default(1)->comment('1 for social account take from user, 0 for not take');

                // kyc back part required
                $table->boolean('kyc_back_part')->default(0)->comment('0=>only front, 1=>front and back part are required');
                $table->tinyInteger('notification_tour')->default(0)->comment('check admin info for notification');
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
        Schema::dropIfExists('system_configs');
    }
};
