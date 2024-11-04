<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private $connection;

    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('MQ_URL'),
        );
        // $this->()rabbitMQService->sendMessage();


        $this->channel = $this->connection->channel();
    }
    public function sendMessage(int $message)
    {
        $this->channel->queue_declare('cleanables', true, true, false, false);
        $mssg = new AMQPMessage($message);
        $this->channel->basic_publish($mssg, '', 'cleanables');
    }
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
