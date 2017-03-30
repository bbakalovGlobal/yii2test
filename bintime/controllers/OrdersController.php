<?php

namespace frontend\modules\bintime\controllers;

use frontend\modules\bintime\models\Products2;
use Yii;
use frontend\modules\bintime\models\Products;
use frontend\modules\bintime\models\Orders;
//use frontend\modules\bintime\models\OrdersSearch;
use frontend\modules\bintime\models\OrdersElastic;
use frontend\modules\bintime\models\OrdersElasticSearch;
use frontend\modules\bintime\workers\OrderSendWorker;
//use yii\base\Exception;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersElasticSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        //create some fake products for orders
        if(empty(Products::find()->all())){
            $productModel = new Products();
            $productModel->createFakeProducts(5);
        }

        if ($model->load(Yii::$app->request->post())) {
            $product = Products::findOne($model->product_name);
            if (!is_null($product)) {
                $attributes = $product->getAttributes();
                $model->orders = [
                    'manufacturer_name' => $attributes['manufacturer_name'],
                    'product_name' => $attributes['product_name'],
                    'quantity' => $model->getAttribute('quantity'),
                    'price_per_item' => $attributes['price'],
                ];
            }

            if ($model->save()) {
                /**
                 * Add data to elastic
                 */
                $orderElasticModel = new OrdersElastic();
                $orderElasticModel->createOrder($model);
                /**
                 * Send email via rabbitMQ worker
                 */
                $msgData = json_encode(['email' => $model->customer_email, 'msg' => 'Order was created.']);
                OrderSendWorker::execute($msgData, 'bintime_orders');
            }

            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'products' => new Products(),
            ]);
        }
    }

    /**
     * NOTE: temporary no supported
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        throw new HttpException(503, 'Sorry, service temporary unavailable.');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * NOTE: temporary no supported
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        throw new HttpException(503, 'Sorry, service temporary unavailable.');
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /***
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
