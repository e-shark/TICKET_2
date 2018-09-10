<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\facilityeq\models\Company */

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
$this->title ='Список компаний';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['company/index']];
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['company/index'], ['class'=>'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)','dateFormat' => 'dd-MM-yyyy',],
        'attributes' => [
            //'id',
            //'companyname',
            [
                'attribute' =>'companyname',
                'label' => 'Краткое наименование',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyfullname',
            [
                'attribute' =>'companyfullname',
                'label' => 'Полное наименование',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companynameeng',
            [
                'attribute' =>'companynameeng',
                'label' => 'Наименование на английском',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companycode',
            [
                'attribute' =>'companycode',
                'label' => 'Код',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companytaxcode',
            [
                'attribute' =>'companytaxcode',
                'label' => 'Номер свид. налогоплательщика',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companydate',
            [
                'attribute' =>'companydate',
                'label' => 'Дата регистрации',  
                'group'=>true,
                'format' => 'date',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyphone',
            [
                'attribute' =>'companyphone',
                'label' => 'Телефон',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyfax',
            [
                'attribute' =>'companyfax',
                'label' => 'Факс',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyemail:email',
            [
                'attribute' =>"companyemail",
                'label' => 'Электронная почта',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyurl',
            [
                'attribute' =>'companyurl',
                'label' => 'Сайт',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyzip',
            [
                'attribute' =>'companyzip',
                'label' => 'Индекс',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyaddress',
            [
                'attribute' =>'companyaddress',
                'label' => 'Адрес',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyrole',
            [
                'attribute' =>'companyrole',
                'label' => 'Роль',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companydescription',
            [
                'attribute' =>'companydescription',
                'label' => 'Описание',  
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            //'companyform_id',
            [
                'attribute' =>'companyform_id',
                'label' => 'Форма собственности',
                'value'=> $model->companyform->companyformname,
                'group'=>true,
                'format' => 'raw',            
                'groupOptions'=>['class'=>'text-left','style'=>'width:50.1%'],
                'contentOptions' => ['class' => 'bg-red','style'=>'width:50.1%'],    
                'captionOptions' => ['tooltip' => 'Tooltip','style'=>'width:50.1%'], 
            ],
            


        ],
    ]) ?>

</div>
