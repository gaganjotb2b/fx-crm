<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventColumnToActivityLogTable extends Migration
{
    public function up()
    {
        $tableName = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection');

        if (Schema::connection($connection)->hasTable($tableName)) {
            Schema::connection($connection)->table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn('activity_log', 'event')) {
                    $table->string('event')->nullable()->after('subject_type');
                }
            });
        }
    }

    public function down()
    {
        $tableName = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection');

        if (Schema::connection($connection)->hasTable($tableName)) {
            Schema::connection($connection)->table($tableName, function (Blueprint $table) {
                if (Schema::hasColumn('activity_log', 'event')) {
                    $table->dropColumn('event');
                }
            });
        }
    }
}
