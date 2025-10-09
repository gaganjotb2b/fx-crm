<?php

use App\Models\Deposit;
use App\Models\TriggerFlug;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        $prefix = config('database.connections.mysql.prefix');
        $tableName = $prefix . (new Deposit())->getTable();
        // trigger table
        $trigger_flugs = $prefix . (new TriggerFlug())->getTable();
        DB::unprepared("
            CREATE TRIGGER deposit_update AFTER UPDATE ON $tableName
            FOR EACH ROW
            BEGIN
                IF NEW.admin_log <=> OLD.admin_log AND NEW.updated_at <=> OLD.updated_at THEN
                    UPDATE $trigger_flugs
                    SET deposit = 1,
                        deposit_log = JSON_OBJECT(
                            'old_id',OLD.id,
                            'new_id',NEW.id,
                            'old_user_id', OLD.user_id,
                            'new_user_id', NEW.user_id,
                            'old_amount', OLD.amount,
                            'new_amount', NEW.amount,
                            'old_approved_status', OLD.approved_status,
                            'new_approved_status', NEW.approved_status
                        )   
                    WHERE id = 1;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS deposit_update");
    }
};
