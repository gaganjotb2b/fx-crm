<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminBank;  // Adjust this based on your actual model

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
        $tableName = $prefix . (new AdminBank())->getTable();
        // return $tableName;
        // try {
            DB::unprepared("
                CREATE TRIGGER admin_banks_update AFTER UPDATE ON $tableName
                FOR EACH ROW
                BEGIN
                    IF NEW.admin_log <=> OLD.admin_log AND NEW.updated_at <=> OLD.updated_at THEN
                        UPDATE vp_trigger_flugs
                        SET admin_bank = 1
                        WHERE id = 1;
                    END IF;
                END
            ");
        // } catch (\Throwable $th) {
        //     // Handle any exceptions if needed
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('database.connections.mysql.prefix');
        $tableName = $prefix . (new AdminBank())->getTable();

        DB::unprepared("DROP TRIGGER IF EXISTS admin_banks_update");
    }
};

