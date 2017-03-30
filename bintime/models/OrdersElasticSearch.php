<?php

namespace frontend\modules\bintime\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrdersSearchElastic represents the model behind the search form about `frontend\modules\bintime\models\OrdersElastic`.
 */
class OrdersElasticSearch extends OrdersElastic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
        //TODO: implement search functionality
//        return [
//            [['order_id','created_at','customer_name',
//                    'customer_surname',
//                    'customer_email',
//                    'total_price'
//                ],
//                'safe'
//            ]
//        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $request
     * @return ActiveDataProvider
     */
    public function search($request)
    {
//        if (empty($request['OrdersElasticSearch'])) {
        $dataProvider = new ActiveDataProvider([
            'query' => OrdersElastic::find(),
            'pagination' => ['pageSize' => 10],
        ]);
//        }
        //TODO: implement search functionality
//        $search = $request['OrdersElasticSearch'];
//        $query = OrdersElastic::find();
//        $query->query(['match' => ['order_id' => $search['order_id']]]);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => ['pageSize' => 10],
//
//        ]);

        return $dataProvider;
    }
}
