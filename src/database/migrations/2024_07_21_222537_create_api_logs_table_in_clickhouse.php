<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateApiLogsTableInClickhouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS api_logs (
                timestamp DateTime,
                method String,
                data String
            ) ENGINE = MergeTree()
            ORDER BY (timestamp, method)
        ";

        DB::connection('clickhouse')->statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
