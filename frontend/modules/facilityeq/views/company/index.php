<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\facilityeq\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочники'; 
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title ='Список компаний';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['company/index']];
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?><br> 
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'companyname',
            [
                'attribute' => 'companyname',
                'format' => 'raw',
                'label'  => 'Краткое наименование',
                'value'=>function ($model){
                        return Html::a($model->companyname,['company/view','id'=>$model->id]);
                    }
               
            ],
            //'companyfullname',
            [
                'attribute' => 'companyfullname',
                'format' => 'raw',
                'label'  => 'Полное наименование',
                #'value'=>function ($model){
                #        return Html::a($model->companyfullname,['company/view','id'=>$model->id]);
                #    }
               
            ],
            //'companynameeng',
            //'companycode',
            //'companytaxcode',
            //'companydate',
            //'companyphone',
            //'companyfax',
            //'companyemail:email',
            //'companyurl',
            //'companyzip',
            //'companyaddress',
            //'companyrole',
            //'companydescription',
            //'companyform_id',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
