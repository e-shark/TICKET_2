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

    $this->title = 'Справочники';
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
    $this->title ='Список оборудования';
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['elevator/index']];

    $this->title = $model->elfacility->myDistr .', '. $model->elfacility->myFasName;    
    $this->params['breadcrumbs'][] = ['label' => $model->elfacility->myFasName];

    $mylabel_eltype = isset($model->eltype) ? ", $model->eltype" : " ";
    $mylabel_elporchpos = isset($model->elporchpos) ? ", $model->elporchpos":" " ;
    $mylabel = "$model->myDeviceTypeName инв. № $model->elinventoryno" . "$mylabel_eltype" . "$mylabel_elporchpos";
    
    $ohcolumns=[
        [
            'label' => $mylabel ,
            'content' => $this->render('_viewtab', ['model' => $model]),
            'active' => true
        ],
        [
            'label' => "Документы на устройства",
            'content' => $this->context->renderpartial('_fototab', ['model' => $model]),
        ]
    ];

?>
<div class="elevator-view">

    <?= Tabs::widget(['items' => $ohcolumns]);?>


</div>
