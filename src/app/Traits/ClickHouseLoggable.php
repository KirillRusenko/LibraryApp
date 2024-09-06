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
     * @param string $timestamp
     * @param string $uri
     * @param string $method
     * @param string $data
     * @return void
     */
    protected function logToClickHouse(string $timestamp, string $uri, string $method, string $data,): void
    {
        try {
            $connection = DB::connection('clickhouse');

            $timestamp = $timestamp?? date('Y-m-d H:i:s');

            $connection->table('api_logs')->insert([
                'timestamp' => $timestamp,
                'uri' => $uri,
                'method' => $method,
                'data' => $data,
            ]);

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
