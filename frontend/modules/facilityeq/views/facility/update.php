<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Facility */



//$streetdistrict = '_ ' .$model->fastreettype . '_ ' . $model->fastreetname;
if ($model->fasectionno)
{
    $sectionnu= ' секция ' . $model->fasectionno;
}
else
{
    $sectionnu = ' ';
}

$fa = $model->myFasName ;

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title ='Список домов';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['facility/index']];
$this->title = 'Изменить дом: ' . $fa;
$this->params['breadcrumbs'][] = ['label' => $this->title ];


?>
<div class="facility-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'searchModel' => $searchModel,
    ]) ?>

</div>
