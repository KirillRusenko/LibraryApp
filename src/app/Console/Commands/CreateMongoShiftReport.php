<?php

namespace App\Console\Commands;

use App\Services\MongoDBService;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class CreateMongoShiftReport extends Command
{
    protected $signature = 'shift:create-reports';
    protected $description = 'Create shift reports from RabbitMQ and save to MongoDB';

    protected $mongoDBService;
    protected $rabbitMQService;

    public function __construct(MongoDBService $mongoDBService, RabbitMQService $rabbitMQService)
    {
        parent::__construct();
        $this->mongoDBService = $mongoDBService;
        $this->rabbitMQService = $rabbitMQService;
    }

    public function handle()
    {

        $this->rabbitMQService->consumeMessages('shift_reports', function ($msg) {
            $reportData = json_decode($msg->body, true);

            try {
                $insertedId = $this->mongoDBService->insertDocument('shift_reports', $reportData);
                $this->info("Created and saved shift report to MongoDB with ID: " . $insertedId);
            } catch (\Exception $e) {
                $this->error("Error saving shift report to MongoDB: " . $e->getMessage());
            }
        });

        $this->rabbitMQService->close();
        $this->info("No more messages in the queue, stopping consumption...");

        return Command::SUCCESS;
    }
}
