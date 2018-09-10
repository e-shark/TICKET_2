<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\modules\facilityeq\models\District;
use frontend\modules\facilityeq\models\Street;

/* @var $this yii\web\View */
/* @var $model frontend\models\Street */
/* @var $form yii\widgets\ActiveForm */
//        Yii::$app->session->setFlash('success', '_  params:' . nl2br("\n") . 
//            '1: ' . $model->streetdistrict . nl2br("\n") . 
//            '2: ' . $model->streettype . nl2br("\n") .  
//            '3: ' . $model->streetname);

session_start();

/*$msg =  "par1: ".  $_SESSION['el.eldevicetype'] . nl2br("\n");
$msg .= "par2: ".  $_SESSION['el.eldistrict'] . nl2br("\n");
$msg .= "par3: ".  $_SESSION['el.elstreettype'] . nl2br("\n");
$msg .= "par4: ".  $_SESSION['el.elstreetname'] . nl2br("\n");
$msg .= "par5: ".  $_SESSION['el.elfacility_id'] . nl2br("\n");
echo ( $msg );*/


?>

<div class="street-form">

    <?php $form = ActiveForm::begin(); ?>

 
        <?=  $form->field($model, 'streetlocality_id',
            ['template' => '<div class=mclass2> <label> <span>Населенный пункт</span>{input}</label>{error}</div>',]
            )->dropDownList( ['159'=>'Харьков'],
                    [   
                        'maxlength' => true,
                        'style'=>'width:300px',
                        //'disabled'=>'disabled'
                    ]    
            )
        ?>
 
        <?=  $form->field($model, 'streetdistrict',
            ['template' => '<div class=mclass2> <label> <span>Район</span>{input}</label>{error}</div>',]
            )->dropDownList( 
                ArrayHelper::map(
                    District::find()->select(['districtname'])->where(['districtlocality_id' => 159])->all(), 'districtname', 'districtname'),
                    [
                        $model->streetdistrict => ['selected' => true],
                        'maxlength' => true,
                        'style'=>'width:300px',
                    ]
            ) 
        ?>
        
        <?=  $form->field($model, 'streettype',
            ['template' => '<div class=mclass2> <label> <span>Тип улицы</span>{input}</label>{error}</div>',]
            )->dropDownList( 
                ArrayHelper::map(
                    Street::find()->all(), 'streettype', 'streettype'),
                    [
                        $model->streettype => ['selected' => true],
                        'maxlength' => true,
                        'style'=>'width:300px',
                    ]
            ) 
        ?>

        <?= $form->field($model, 'streetname', [
            'template' => '<div class=mclass2> 
                        <label> <span>Наименование</span>{input}</label>{error}</div>',
        ])->textInput(['style'=>'width:300px','maxlength' => true]) ?>


        <?= $form->field($model, 'streetnameru', [
            'template' => '<div class=mclass2> 
                        <label> <span>Наименование русское</span>{input}</label>{error}</div>',
        ])->textInput(['style'=>'width:300px','maxlength' => true]) ?>

        <?= $form->field($model, 'streetnameeng', [
            'template' => '<div class=mclass2> 
                        <label> <span>Наименование английское</span>{input}</label>{error}</div>',
        ])->textInput(['style'=>'width:300px','maxlength' => true]) ?>

        <!-- $form->field($model, 'streetcode', [
            'template' => '<div class=mclass2> 
                        <label> <span>Код улицы</span>{input}</label>{error}</div>',
        ])->textInput(['style'=>'width:300px','maxlength' => true]) ?-->

        <!--?= $form->field($model, 'streetzip', [
            'template' => '<div class=mclass2> 
                        <label> <span>Почтовый индекс</span>{input}</label>{error}</div>',
        ])->textInput(['style'=>'width:300px','maxlength' => true]) ?-->
       

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>&nbsp;&nbsp;
        <?= Html::a('Назад к списку улиц', ['street/index',
                'StreetSearch[streetdistrict]'=>$_SESSION['st.streetdistrict'],
                'StreetSearch[streettype]'=>$_SESSION['st.streettype'],
                'StreetSearch[streetnameru]'=>$_SESSION['st.streetnameru'] ], ['class'=>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
