<?php
namespace frontend\modules\bintime\console;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class OrdersReceiveWorker
{
    /**
     * Listen incoming messages
     * @param $queueName
     */
    public function listen($queueName)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, true, false, false);

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queueName, '', false, false, false, false, array($this, 'process'));

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    /**
     * Process received request
     *
     * @param AMQPMessage $msg
     */
    public function process(AMQPMessage $msg)
    {
        $this->sendEmail($msg);
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    /**
     * Sends email
     *
     * @return OrdersReceiveWorker
     */
    private function sendEmail($msg)
    {
        $msg = json_decode($msg->getBody());
        /**
         * Can't prepare console object for yii instance
         */
//        Yii::$app->mailer->compose()
//            ->setTo($msg->email)
//            ->setFrom(Yii::$app->params['adminEmail'])
//            ->setSubject('Order creation')
//            ->setTextBody($msg->msg)
//            ->send();
        echo "Email '{$msg->msg}' was sent to {$msg->email}\n";
        return $this;
    }
}

$worker = new OrdersReceiveWorker();
$worker->listen('bintime_orders');