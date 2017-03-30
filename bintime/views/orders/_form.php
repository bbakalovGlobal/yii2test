<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\bintime\models\Orders */
/* @var $products frontend\modules\bintime\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'customer_name') ?>
    <?= $form->field($model, 'customer_surname') ?>
    <?= $form->field($model, 'customer_email') ?>
    <?= $form->field($model, 'product_name')->dropDownList($products->getProductsDropdown()); ?>
    <?= $form->field($model, 'quantity') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create order' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
