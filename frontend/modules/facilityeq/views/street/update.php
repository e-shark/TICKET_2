<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Street */

$streetdistrict = mb_convert_case($model->streetdistrict, MB_CASE_TITLE, "UTF-8"). " р-н, " . $model->streettype . " " . $model->streetnameru;  //($model->streetdistrict));

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title ='Список улиц';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['street/index']];
$this->title = 'Изменить улицу: ' . $streetdistrict;
$this->params['breadcrumbs'][] = ['label' => $this->title ];


?>
<div class="street-update">

    <h1><?= Html::encode($this->title) ?></h1> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
