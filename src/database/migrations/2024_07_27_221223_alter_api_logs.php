<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = "
            ALTER TABLE api_logs
            ADD COLUMN uri String AFTER timestamp

        ";

        DB::connection('clickhouse')->statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
