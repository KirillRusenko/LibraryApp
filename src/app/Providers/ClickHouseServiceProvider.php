<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use ClickHouseDB\Client;

class ClickHouseServiceProvider extends ServiceProvider
{
    public function register()
    {
        Connection::resolverFor('clickhouse', function ($connection, $database, $prefix, $config) {
            $client = new Client($config);
            return new Connection($client, $database, $prefix, $config);
        });
    }

    public function boot()
    {

    }
}
