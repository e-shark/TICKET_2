<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\facilityeq\models\CompanySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'companyname') ?>

    <?= $form->field($model, 'companyfullname') ?>

    <?= $form->field($model, 'companynameeng') ?>

    <?= $form->field($model, 'companycode') ?>

    <?php // echo $form->field($model, 'companytaxcode') ?>

    <?php // echo $form->field($model, 'companydate') ?>

    <?php // echo $form->field($model, 'companyphone') ?>

    <?php // echo $form->field($model, 'companyfax') ?>

    <?php // echo $form->field($model, 'companyemail') ?>

    <?php // echo $form->field($model, 'companyurl') ?>

    <?php // echo $form->field($model, 'companyzip') ?>

    <?php // echo $form->field($model, 'companyaddress') ?>

    <?php // echo $form->field($model, 'companyrole') ?>

    <?php // echo $form->field($model, 'companydescription') ?>

    <?php // echo $form->field($model, 'companyform_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
