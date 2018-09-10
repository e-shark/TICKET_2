<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\facilityeq\models\Company */

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title ='Список компаний';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['company/index']];
$this->title = 'Изменить компанию: ';
$this->params['breadcrumbs'][] = ['label' => $this->title ];
?>
<div class="company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
