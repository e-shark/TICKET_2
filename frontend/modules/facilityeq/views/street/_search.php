<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\StreetSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="street-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'streetdistrict') ?>

    <?= $form->field($model, 'streetname') ?>

    <?= $form->field($model, 'streetnameru') ?>

    <?= $form->field($model, 'streetnameeng') ?>

    <?php // echo $form->field($model, 'streettype') ?>

    <?php // echo $form->field($model, 'streetcode') ?>

    <?php // echo $form->field($model, 'streetzip') ?>

    <?php // echo $form->field($model, 'streetlocality_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
