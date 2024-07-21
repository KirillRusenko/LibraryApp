<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

trait ClickHouseLoggable
{
    /**
     * Log an action to ClickHouse.
     *
     * @param string $method
     * @param array $data
     * @return void
     */
    protected function logToClickHouse(string $method, array $data = []): void
    {
        try {
            $connection = DB::connection('clickhouse');

            $timestamp = date('Y-m-d H:i:s');
            $jsonData = json_encode($data);

            $sql = "
                INSERT INTO api_logs (timestamp, method, data)
                VALUES ('{$timestamp}', '{$method}', '{$jsonData}')
            ";

            $connection->statement($sql);

        } catch (Exception $e) {
            Log::error('Failed to log to ClickHouse: ' . $e->getMessage(), [
                'method' => $method,
                'data' => $data,
                'exception' => $e
            ]);
        }
    }
}
