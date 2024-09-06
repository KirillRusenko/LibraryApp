<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct(
    ) {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD')
        );
        $this->channel = $this->connection->channel();
    }

    public function publishMessage($queue, $message)
    {
        $this->channel->queue_declare($queue, false, true, false, false);
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function consumeMessages($queue, $callback)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $this->channel->basic_consume(
            $queue,
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        while ($this->channel->is_consuming()) {
            try {
                $this->channel->wait(null, false, 1); // Ожидание с таймаутом 1 секунда
            } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {
                break;
            }
        }
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
