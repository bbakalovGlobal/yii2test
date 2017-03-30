<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\bintime\models\Orders */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            'customer_name',
            'customer_surname',
            'customer_email',
            [
                'label' => 'Product name',
                'value' => $model->orders['product_name'],
            ],
            'quantity',
            [
                'label' => 'Price per item',
                'value' => $model->orders['price_per_item'],
            ],
        ],
    ]) ?>
</div>
