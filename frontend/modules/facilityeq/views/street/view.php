<?php

    use yii\helpers\Html;
    use yii\widgets\DetailView;
    use yii\widgets\Breadcrumbs;

    /* @var $this yii\web\View */
    /* @var $model frontend\models\Street */
    session_start();

    /*$msg =  "par1: ".  $_SESSION['el.eldevicetype'] . nl2br("\n");
    $msg .= "par2: ".  $_SESSION['el.eldistrict'] . nl2br("\n");
    $msg .= "par3: ".  $_SESSION['el.elstreettype'] . nl2br("\n");
    $msg .= "par4: ".  $_SESSION['el.elstreetname'] . nl2br("\n");
    $msg .= "par5: ".  $_SESSION['el.elfacility_id'] . nl2br("\n");
    echo ( $msg );*/

    $streetdistrict = mb_convert_case($model->streetdistrict, MB_CASE_TITLE, "UTF-8"). " р-н, " . $model->streettype . " " . $model->streetnameru;  //($model->streetdistrict));
    $this->title = 'Справочники';
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
    $this->title ='Список улиц';
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['street/index']];
    $this->title = $streetdistrict;
    $this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="street-view">

    <h1><?= Html::encode( $streetdistrict) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['street/index',
                'StreetSearch[streetdistrict]'=>$_SESSION['st.streetdistrict'],
                'StreetSearch[streettype]'=>$_SESSION['st.streettype'],
                'StreetSearch[streetnameru]'=>$_SESSION['st.streetnameru'] ], ['class'=>'btn btn-success']) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
        'attributes' => [
            [
                'attribute' =>'streetlocality_id',
                 'label' => 'Населенный пункт',
                 'value'=>'Харьков'
            ],

            [
                'attribute' =>'streetdistrict',
                 'label' => 'Район',
            ],

            [
                'format' => 'raw',
                'label'  => 'Кол. Домов',
                'value'=>function ($model) {
                    return Html::a($model->countfacilities,['facility/index','FacilitySearch[fastreet_id]'=>$model->id]);
                   }
            ],

            [
                'attribute' =>'streettype',
                'label' => 'Тип улицы',
            ],

            [
                'attribute' =>'streetname',
                'label' => 'Наименование',
            ],

            [
                'attribute' =>'streetnameru',
                'label' => 'Наименование русское',
                'sort' => [
                'defaultOrder' => [
                    'streetnameru' => SORT_ASC
                    ]
                ],
            ],
            
            [
                'attribute' =>'streetnameeng',
                'label' => 'Наименование английское',
            ],
      
            
        ],
    ]) ?>

</div>
