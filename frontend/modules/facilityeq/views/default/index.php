<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
?>
<div>

    <p>
        <?= Html::a('Список улиц', ['street/index'], ['class'=>'btn btn-success']) ?>
    </p>

    <p>  
        <?= Html::a('Список домов', ['facility/index'], ['class'=>'btn btn-success']) ?>
    </p>

    <p>  
        <?= Html::a('Список оборудования', ['elevator/index'], ['class'=>'btn btn-success']) ?>
    </p>

    <p>  
        <?= Html::a('Список компаний', ['company/index'], ['class'=>'btn btn-success']) ?>
    </p>

    <p>  
        <?= Html::a('Список подразделений', ['/employeeeq/division/index'], ['class'=>'btn btn-success']) ?>
    </p>

    <p>  
        <?= Html::a('Список должностей',  ['/employeeeq/occupation/index'], ['class'=>'btn btn-success']) ?>
    </p>

    <p>  
        <?= Html::a('Список персонала',  ['/employeeeq/employee/index'], ['class'=>'btn btn-success']) ?>

</div>  


<?php/*
<div>  
<p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'streetdistrict',
                'format' => 'raw',
                'label'  => 'Район',
            ],

            [
                'attribute' => 'streetname',
                'format' => 'raw',
                'label'  => 'Название улицы',
            ],

            [
                'attribute' => 'streetnameru',
                'format' => 'raw',
                'label'  => 'Название улицы (рус)',
            ],
            
            [
                'attribute' => 'streettype',
                'filter' => [
                    'б-р.' => 'б-р.',
                    'в-д.' => 'в-д.',
                    'вул.' => 'вул.',
                    'м-н.' => 'м-н.',
                    'наб.' => 'наб.',
                    'Площа' => 'Площа',
                    'пр-д.' => 'пр-д.',
                    'пр.' => 'пр.',
                    'пров.' => 'пров.',
                    'сел.' => 'сел.',
                    'ст.' => 'ст.',
                    'Тупик' => 'Тупик',
                    'узв.' => 'узв.',
                    'шосе.' => 'шосе.',
                ],
                              
            ],

            ['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>
 </p>
</div>
*/?>
