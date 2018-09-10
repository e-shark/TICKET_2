<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model frontend\models\Facility */
session_start();
/*$msg =  "par1: ".  $_SESSION['fa.fadistrict_id'] . nl2br("\n");
$msg .= "par2: ".  $_SESSION['fa.fastreettype'] . nl2br("\n");
$msg .= "par3: ".  $_SESSION['fa.fastreetname'] . nl2br("\n");
$msg .= "par4: ".  $_SESSION['fa.fabuildingno'] . nl2br("\n");
$msg .= "par5: ".  $_SESSION['fa.elfacility'] . nl2br("\n");
echo ( $msg );*/

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title ='Список домов';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['facility/index']];

if ($model->fasectionno)
{
    $sectionnu= " секция " . $model->fasectionno;
}
else
{
    $sectionnu= " ";
}

$fa = $model->fatype . " № " . $model->faaddressno . " " . $sectionnu ;  //($model->streetdistrict));
$this->title = $fa;
$this->params['breadcrumbs'][] = ['label' => $model->myFasName];

?>

<div class="facility-view">

    <h1><?= Html::encode($model->myFasName) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['facility/index',                
                'FacilitySearch[fadistrict_id]' => $_SESSION['fa.fadistrict_id'],
                'FacilitySearch[fastreettype]'  => $_SESSION['fa.fastreettype'],
                'FacilitySearch[fastreetname]'  => $_SESSION['fa.fastreetname'],
                'FacilitySearch[fabuildingno]'  => $_SESSION['fa.fabuildingno'],
                'FacilitySearch[elfacility]'    => $_SESSION['fa.elfacility'] ], ['class'=>'btn btn-success']) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
        'attributes' => 
        [
            //faaddressno -  адрес объекта 
            //fabuildingno - номер здания 
            //fasectionno -  номер секции ,
            [
                'attribute' =>'fabuildingno',
                'label' => 'Номер дома',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'label' => 'Район',               
                'value'=>$model->myDistr,
                'group'=>true,
                'format' => 'raw', 
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'label' => 'Улица',               
                'value'=>$model->fastreet->streettype.' '. $model->fastreet->streetnameru,
                'group'=>true,
                'format' => 'raw', 
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ], 

            [
                'attribute' =>'facodesvc',
                'label' => 'Код ЖКХ',
                'group'=>true,
                'format' => 'raw', 
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'attribute' =>'facode',
                'label' => 'Глобальный код',
                'group'=>true,
                'format' => 'raw', 
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'attribute' =>'fainventoryno',
                'label' => 'Инвентарный номер',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'attribute' =>'fastoreysnum',
                'label' => 'Количество этажей',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'attribute' =>'faporchesnum',
                'label' => 'Количество подъездов',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            
            [
                'attribute' =>'falatitude',
                'label' => 'Географическая широта',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'attribute' =>'falongitude',
                'label' => 'Географическая долгота',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

        ],
    ]) ?>

  
</div>




