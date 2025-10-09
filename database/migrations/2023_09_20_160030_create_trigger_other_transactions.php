<?php

use App\Models\OtherTransaction;
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
        $tableName = $prefix . (new OtherTransaction())->getTable();
        // trigger table
        $trigger_flugs = $prefix . (new TriggerFlug())->getTable();
        DB::unprepared("
            CREATE TRIGGER other_transaction_update AFTER UPDATE ON $tableName
            FOR EACH ROW
            BEGIN
                IF NEW.admin_log <=> OLD.admin_log AND NEW.updated_at <=> OLD.updated_at THEN
                    UPDATE $trigger_flugs
                    SET withdraw = 1,
                        withdraw_log = JSON_OBJECT(
                            'old_id', OLD.id,
                            'new_id', NEW.id,
                            'old_transaction_type', OLD.transaction_type,
                            'new_transaction_type', NEW.transaction_type,
                            'old_crypto_instrument', OLD.crypto_instrument,
                            'new_crypto_instrument', NEW.crypto_instrument,
                            'old_block_chain', OLD.block_chain,
                            'new_block_chain', NEW.block_chain,
                            'old_gateway_name', OLD.gateway_name,
                            'new_gateway_name', NEW.gateway_name,
                            'old_crypto_address', OLD.crypto_address,
                            'new_crypto_address', NEW.crypto_address,
                            'old_crypto_amount', OLD.crypto_amount,
                            'new_crypto_amount', NEW.crypto_amount,
                            'old_account_name', OLD.account_name,
                            'new_account_name', NEW.account_name,
                            'old_account_email', OLD.account_email,
                            'new_account_email', NEW.account_email,
                            'old_payment_id', OLD.payment_id,
                            'new_payment_id', NEW.payment_id,
                            'old_status', OLD.status,
                            'new_status', NEW.status,
                            'old_ip_address', OLD.ip_address,
                            'new_ip_address', NEW.ip_address,
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
        DB::unprepared("DROP TRIGGER IF EXISTS other_transaction_update");
    }
};
