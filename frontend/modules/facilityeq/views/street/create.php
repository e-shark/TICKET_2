<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Street */

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title = 'Добавить улицу';
$this->params['breadcrumbs'][] = $this->title;



?>
<br>
<div class="street-create">

    <h1><?= 'НОВАЯ УЛИЦА' ?></h1>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
