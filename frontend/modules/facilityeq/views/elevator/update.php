<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Elevator */

//$fa = "дом № " . $model->elfacility->faaddressno . " " . $sectionnu ;  //($model->streetdistrict));

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title ='Список оборудования';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['elevator/index']];
$this->title = 'Изменить оборудование: ' . $fa;
$this->params['breadcrumbs'][] = ['label' => $this->title ];
?>

<div class="elevator-update">

    <h1><?= Html::encode($this->title) ?></h1><br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
