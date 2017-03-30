<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\bintime\models\Orders */
/* @var $products frontend\modules\bintime\models\Products */

$this->title = 'Create Order';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'products' => $products
    ]) ?>

</div>
