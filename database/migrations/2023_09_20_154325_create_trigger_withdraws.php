<?php

use App\Models\TriggerFlug;
use App\Models\Withdraw;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
        $tableName = $prefix . (new Withdraw())->getTable();
        // trigger table
        $trigger_flugs = $prefix . (new TriggerFlug())->getTable();
        DB::unprepared("
            CREATE TRIGGER withdraw_update AFTER UPDATE ON $tableName
            FOR EACH ROW
            BEGIN
                IF NEW.admin_log <=> OLD.admin_log AND NEW.updated_at <=> OLD.updated_at THEN
                    UPDATE $trigger_flugs
                    SET withdraw = 1,
                        withdraw_log = JSON_OBJECT(
                            'old_id', OLD.id,
                            'new_id', NEW.id,
                            'old_user_id', OLD.user_id,
                            'new_user_id', NEW.user_id,
                            'old_amount', OLD.amount,
                            'new_amount', NEW.amount,
                            'old_approved_status', OLD.approved_status,
                            'new_approved_status', NEW.approved_status,
                            'old_transaction_type', OLD.transaction_type,
                            'new_transaction_type', NEW.transaction_type,
                            'old_bank_account_id', OLD.bank_account_id,
                            'new_bank_account_id', NEW.bank_account_id,
                            'old_wallet_type', OLD.wallet_type,
                            'new_wallet_type', NEW.wallet_type,
                            'old_created_by', OLD.created_by,
                            'new_created_by', NEW.created_by,
                            'old_updated_at', OLD.updated_at,
                            'new_updated_at', NEW.updated_at
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
        DB::unprepared("DROP TRIGGER IF EXISTS withdraw_update");
    }
};
