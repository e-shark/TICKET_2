<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model frontend\models\Elevator */

session_start();

/*$msg =  "par1: ".  $_SESSION['el.eldevicetype'] . nl2br("\n");
$msg .= "par2: ".  $_SESSION['el.eldistrict'] . nl2br("\n");
$msg .= "par3: ".  $_SESSION['el.elstreettype'] . nl2br("\n");
$msg .= "par4: ".  $_SESSION['el.elstreetname'] . nl2br("\n");
$msg .= "par5: ".  $_SESSION['el.elfacility_id'] . nl2br("\n");
echo ( $msg );*/

//$this->title = 'Справочники';
//$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
//$this->title ='Список оборудования';
//$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['elevator/index']];

//$this->title = $model->elfacility->myDistr .', '. $model->elfacility->myFasName;
//$this->params['breadcrumbs'][] = ['label' => $model->elfacility->myFasName];

?>
<div class="elevator-view">

    <p><br>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['elevator/index',
                'ElevatorSearch[eldevicetype]'  => $_SESSION['el.eldevicetype'],
                'ElevatorSearch[eldistrict]'    => $_SESSION['el.eldistrict'],
                'ElevatorSearch[elstreettype]'  => $_SESSION['el.elstreettype'],
                'ElevatorSearch[elstreetname]'  => $_SESSION['el.elstreetname'],
                'ElevatorSearch[elfacility_id]' => $_SESSION['el.elfacility_id'] ], ['class'=>'btn btn-success']) ?>
       
   </p>
    <h4>
            <br><h1>Тип оборудования: <?= Html::encode($model->myDeviceTypeName) ?><br></h1>
            
    </h4><br>

    <h4>
            <h1>Регистрационные данные</h1>
            
    </h4><br>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
        'attributes' => [
            [
                'attribute' =>'elinventoryno', 
                'label' => 'Инвентарный номер',  
                'format' => 'raw',            
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],   

            [
                'attribute' =>'eldate', 
                'label' => 'Дата регистрации',  
                'format' => 'raw',            
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],   

            [
                'label' => 'Район',               
                'value'=>$model->elfacility->myDistr,
                'group'=>true,
                'format' => 'raw', 
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'label' => 'Улица',               
                'value'=>$model->elfacility->fastreet->streettype.' '. $model->elfacility->fastreet->streetnameru,
                'group'=>true,
                'format' => 'raw', 
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ], 

            [
                'label' => 'Дом',  
                'value'=>$model->elfacility->myBuildingNo,
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'attribute' => 'elporchno', 
                'label' => 'Подъезд',  
                'format' => 'raw',            
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],  

            [
                'attribute' => 'elporchpos',
                'label' => 'Положение в подъезде',  
                'format' => 'raw',            
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ], 

            [
                'attribute' => 'eldivision_id',
                'label' => 'Обслуживающее подразделение',  
                'value'=> $model->eldivision->divisionname,
                'format' => 'raw',            
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

            [
                'attribute' => 'elperson_id',
                'label' => 'Закрепленный электромеханик',  
                'value'=> $model->elperson->fullName,
                'format' => 'raw',            
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],

        ],
    ]) ?>

    <h4>
            <h1>Принадлежность оборудования</h1>
            
    </h4><br>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
        'attributes' => [

            [
                //'attribute' =>'elinventoryno', 
                'label' => 'Собственник',  
                'format' => 'raw',   
                'value' => $model->elownercompany->companyname,
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bRtuvalg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],   

            [
                'label' => 'Исполнитель', 
                'format' => 'raw',
                'value' => $model->elservicecompany->companyname, 
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],

            [
                'label' => 'Субподрядчик', 
                'format' => 'raw',
                'value' => $model->elsubservicecompany->companyname, 
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],

        ],
    ]) ?>



    <h4>
            <h1>Системные настройки</h1>
            
    </h4><br>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
        'attributes' => [

            [
                'attribute' =>'elrtu_id', 
                'label' => 'Устройство диспетчеризации',  
                'format' => 'raw',   
                'value' => $model->rtuval != null ? $model->rtuval : null,
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bRtuvalg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],   

            [
                'attribute' =>'elremoteid', 
                'label' => 'Номер в смежной системе ',  
                'format' => 'raw',
                //'value'=> '  ',              
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],

        ],
    ]) ?>




    <h4>
            <h1>Паспортные данные</h1>
            
    </h4><br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' =>'elserialno', 
                'label' => 'Заводской номер',  
                'format' => 'raw',            
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],   

            [
                'attribute' =>'elmodel', 
                'label' => 'Модель',  
                'format' => 'raw',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],
            [
                'attribute' =>'elload', 
                'label' => 'Грузоподъемность',  
                'format' => 'raw',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],
            [
                'attribute' =>'elspeed', 
                'label' => 'Скорость',  
                'format' => 'raw',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],
            [
                'attribute' =>'eltype', 
                'label' => 'Тип',  
                'format' => 'raw',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],
            [
                'attribute' =>'eldoortype', 
                'label' => 'Тип двери',  
                'format' => 'raw',
                'group'=>true,
                'groupOptions'=>['class'=>'text-left','style'=>'width:45.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:45.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:45.1%'], 
            ],
        ],
    ]) ?>

</div>
