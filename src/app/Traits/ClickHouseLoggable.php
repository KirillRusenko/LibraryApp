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
     * @param string $uri
     * @param string $method
     * @param string $data
     * @return void
     */
    protected function logToClickHouse(string $uri, string $method, string $data): void
    {
        try {
            $connection = DB::connection('clickhouse');

            $timestamp = date('Y-m-d H:i:s');

            $sql = "
                INSERT INTO api_logs (timestamp, uri, method, data)
                VALUES ('{$timestamp}', '{$uri}', '{$method}', '{$data}')
            ";

            $connection->statement($sql);

        } catch (Exception $e) {
            Log::error('Failed to log to ClickHouse: ' . $e->getMessage(), [
                'uri' => $uri,
                'method' => $method,
                'data' => $data,
                'exception' => $e
            ]);
        }
    }
}
