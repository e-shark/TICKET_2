<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\facilityeq\models\Company */

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title = 'Добавить компанию';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
