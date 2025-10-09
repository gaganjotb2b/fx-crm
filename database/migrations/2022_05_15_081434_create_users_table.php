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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->engine = "InnoDB";
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->tinyInteger('type')->nullable()->comment('0 for trader, 1 for system, 2 for admin, 3 for corporate, 4 for ib, 5 for manager');
                $table->string('live_status', 20)->default('live')->comment('live or demo');
                $table->string('password');
                $table->string('transaction_password')->nullable();
                $table->boolean("active_status")->default(1)->comment('1 for active status, 0 for disabled status');
                $table->boolean("login_status")->default(0)->comment('1 for true, 0 for false');
                $table->timestamp('email_verified_at')->nullable();
                $table->boolean('g_auth')->default(0)->comment('Google 2 step 1 for enable, 0 for disable');
                $table->boolean('email_auth')->default(0)->comment('Email auth 1 for enable, 0 for disable');
                $table->string('secret_key', 64)->nullable();
                $table->boolean('email_verification')->default(0)->comment('0 for false, 1 for true');
                $table->boolean('commission_operation')->default(0)->comment('0 for false, 1 for true');
                $table->boolean('tmp_pass')->default(0)->comment('0 for false, 1 for true');
                $table->boolean('tmp_tran_pass')->default(0)->comment('0 for false, 1 for true');
                $table->unsignedBigInteger('category_id')->nullable()->comment('references categories table');
                $table->index('category_id');
                $table->unsignedBigInteger('ib_group_id')->nullable()->comment('FK:ib_group_id(id)');
                $table->index('ib_group_id');
                $table->string('client_type', 100)->nullable()->comment('client_type like as demo, live');
                $table->float('app_investment')->default(0);
                $table->boolean('combine_access')->default(0)->comment('0 for false, 1 for true');
                $table->unsignedBigInteger('announcement_id')->nullable()->comment('0 for false, 1 for true');
                $table->index('announcement_id');
                $table->integer('trading_ac_limit')->default(0);
                $table->unsignedBigInteger('client_group_id')->nullable();
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('client_group_id')->references('id')->on('client_groups')->onDelete('cascade');
                $table->foreign('ib_group_id')->references('id')->on('ib_groups')->onDelete('cascade');
                $table->foreign('announcement_id')->references('id')->on('announcements')->onDelete('cascade');
                $table->rememberToken();
                $table->boolean('kyc_status')->default(0)->comment('0=>unverified,1=>verified,2=>pending');
                $table->timestamps();
                $table->timestamp('tmp_trans_pass_expired')->nullable();
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
        Schema::dropIfExists('users');
    }
};
