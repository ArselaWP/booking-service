<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher
{
    public static function publish($queue, $data)
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', '127.0.0.1'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            $queue,
            false,
            true,
            false,
            false
        );

        $message = new AMQPMessage(
            json_encode($data)
        );

        $channel->basic_publish(
            $message,
            '',
            $queue
        );

        $channel->close();
        $connection->close();
    }
}