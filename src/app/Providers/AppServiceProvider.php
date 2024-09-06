<?php

namespace App\Providers;

use App\Services\MongoDBService;
use App\Services\RabbitMQService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MongoDBService::class, function () {
            return new MongoDBService();
        });

        $this->app->singleton(RabbitMQService::class, function () {
            return new RabbitMQService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
