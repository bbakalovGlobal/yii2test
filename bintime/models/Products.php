<?php

namespace frontend\modules\bintime\models;

use Yii;
use \yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "products".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $product_id
 * @property mixed $manufacturer_name
 * @property mixed $product_name
 */
class Products extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['bintime', 'products'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'manufacturer_name',
            'product_name',
            'price'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manufacturer_name', 'product_name','price'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'Product ID',
            'manufacturer_name' => 'Manufacturer Name',
            'product_name' => 'Product Name',
            'price' => 'Price'
        ];
    }

    public function getProductsDropdown()
    {
        $products = [''=>'Please choose product'];
        foreach (Products::find()->select(['_id','product_name'])->asArray()->all() as $product) {
            $products[(string)$product['_id']] = $product['product_name'];
        }
        return $products;
    }

    public function createFakeProducts($qtyOfProducts)
    {
        for($i = 1; $i <= $qtyOfProducts; $i++){
            $product = new self();
            $product->manufacturer_name = "Manufacturer name $i";
            $product->product_name = "Product name $i";
            $product->price = mt_rand(1,100);
            $product->save();
        }
    }
}
