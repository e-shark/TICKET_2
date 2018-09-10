<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\FacilitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="facility-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'facode') ?>

    <?= $form->field($model, 'facodesvc') ?>

    <?= $form->field($model, 'fainventoryno') ?>

    <?= $form->field($model, 'faaddressno') ?>

    <?php // echo $form->field($model, 'fabuildingno') ?>

    <?php // echo $form->field($model, 'fasectionno') ?>

    <?php // echo $form->field($model, 'fastoreysnum') ?>

    <?php // echo $form->field($model, 'faporchesnum') ?>

    <?php // echo $form->field($model, 'fabseries') ?>

    <?php // echo $form->field($model, 'fatype') ?>

    <?php // echo $form->field($model, 'fadescription') ?>

    <?php // echo $form->field($model, 'fadate') ?>

    <?php // echo $form->field($model, 'facomdate') ?>

    <?php // echo $form->field($model, 'fadecomdate') ?>

    <?php // echo $form->field($model, 'faserviceno') ?>

    <?php // echo $form->field($model, 'falatitude') ?>

    <?php // echo $form->field($model, 'falongitude') ?>

    <?php // echo $form->field($model, 'fastreet_id') ?>

    <?php // echo $form->field($model, 'fadistrict_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
