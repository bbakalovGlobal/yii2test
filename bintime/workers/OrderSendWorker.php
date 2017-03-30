<?php
namespace frontend\modules\bintime\workers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class OrderSendWorker
{
    /**
     * Sends task fo creation email to the workers
     *
     * @param string $emailTo
     * @param string $queueName
     * @param array $info
     */
    /**
     * @param $info
     * @param $queueName
     */
    public static function execute($info, $queueName)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, true, false, false);
        $msg = new AMQPMessage($info, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        $channel->basic_publish($msg, '', $queueName);

        $channel->close();
        $connection->close();
    }
}