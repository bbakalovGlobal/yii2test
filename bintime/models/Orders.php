<?php

namespace frontend\modules\bintime\models;

use Yii;
use \yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "orders".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $order_id
 * @property mixed $created_at
 * @property mixed $customer_name
 * @property mixed $customer_surname
 * @property mixed $customer_email
 * @property mixed $orders
 * @property mixed $manufacturer_name
 * @property mixed $product_name
 * @property mixed $quantity
 * @property mixed $price_per_item
 */
class Orders extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['bintime', 'orders'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
//            'order_id',
            'created_at',
            'customer_name',
            'customer_surname',
            'customer_email',
            'orders',
            'manufacturer_name',
            'product_name',
            'quantity',
            'price_per_item',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'customer_name',
                    'customer_surname',
                    'customer_email',
                    'product_name',
                    'quantity',
                ],
                'safe'
            ],
            [['customer_email','product_name', 'quantity'], 'required'],
            [['quantity'], 'integer'],
            ['customer_email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'Order ID',
            'created_at' => 'Created At',
            'customer_name' => 'Customer Name',
            'customer_surname' => 'Customer Surname',
            'customer_email' => 'Customer Email',
            'orders' => 'Orders',
            'manufacturer_name' => 'Manufacturer Name',
            'product_name' => 'Product Name',
            'quantity' => 'Quantity of products',
            'price_per_item' => 'Price Per Item',
        ];
    }
}
