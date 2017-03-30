<?php

namespace frontend\modules\bintime\models;

use Yii;
use yii\elasticsearch\ActiveRecord;
use yii\elasticsearch\Exception;


Class OrdersElastic extends ActiveRecord
{

    /**
     * Get index
     * @return string
     */
    public static function index()
    {
        return 'bintime';
    }

    /**
     * Get type
     * @return string
     */
    public static function type()
    {
        return 'orders';
    }

    /**
     * Get attributes
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'order_id',
            'created_at',
            'customer_name',
            'customer_surname',
            'customer_email',
            'total_price'
        ];
    }

    /**
     * Create order
     * @param Orders $orderModel
     */
    public function createOrder(Orders $orderModel)
    {
        if (!$this->indexExists()) {
            $this->createIndex();
        }
        $this->primaryKey = $orderModel->_id->__toString();
        $this->order_id = $this->primaryKey;
        $this->customer_name = $orderModel->customer_name;
        $this->customer_surname = $orderModel->customer_surname;
        $this->customer_email = $orderModel->customer_email;
        $this->created_at = $orderModel->created_at;
        $this->total_price = !isset($orderModel->orders) ? 0
            : $orderModel->orders['quantity'] * $orderModel->orders['price_per_item'];

        try {
            $this->insert();
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * Create this model's index
     */
    public function createIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();

        $command->createIndex(static::index(), [
            'mappings' => static::mapping(),
        ]);
    }

    /**
     * Delete this model's index
     */
    public static function deleteIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index(), static::type());
    }

    /**
     * Check index existence
     * @param null $index
     * @return boolean
     */
    public function indexExists($index = null)
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $result = is_null($index) ? $command->indexExists(static::index()) : $command->indexExists($index);

        return $result;
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping()
    {
        return [
            static::type() => [
                'properties' => [
                    'order_id' => ['type' => 'text', 'fielddata' => 'true'],
                    'created_at' => ['type' => 'date'],
                    'customer_name' => ['type' => 'text', 'fielddata' => 'true'],
                    'customer_surname' => ['type' => 'text', 'fielddata' => 'true'],
                    'customer_email' => ['type' => 'text', 'fielddata' => 'true'],
                    'total_price' => ['type' => 'float'],
                ]
            ],
        ];
    }

    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }
}