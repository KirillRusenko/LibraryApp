<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use App\Traits\ClickHouseLoggable;

class ProcessApiLogs extends Command
{
    use ClickHouseLoggable;

    protected $signature = 'api:process-logs';
    protected $description = 'Process API logs from RabbitMQ';
    protected $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        parent::__construct();
        $this->rabbitMQService = $rabbitMQService;
    }

    public function handle()
    {

        $this->rabbitMQService->consumeMessages('api_logs', function ($msg) {
            $log = json_decode($msg->body, true);

            $this->logToClickHouse($log['timestamp'], $log['uri'], $log['method'], json_encode($log['content']));

            $this->info("Processed log and saved to ClickHouse: " . $msg->body);
        });
        $this->rabbitMQService->close();
        $this->info("No more messages in the queue, stopping consumption...");
        return Command::SUCCESS;
    }
}
