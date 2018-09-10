<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'districtname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'districtnameeng')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'districtcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'districtlistno')->textInput() ?>

    <?= $form->field($model, 'districtlocality_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
