<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\modules\facilityeq\models\Locality;
use frontend\modules\facilityeq\models\District;
use frontend\modules\facilityeq\models\Street;

session_start();

/*$msg =  "par1: ".  $_SESSION['el.eldevicetype'] . nl2br("\n");
$msg .= "par2: ".  $_SESSION['el.eldistrict'] . nl2br("\n");
$msg .= "par3: ".  $_SESSION['el.elstreettype'] . nl2br("\n");
$msg .= "par4: ".  $_SESSION['el.elstreetname'] . nl2br("\n");
$msg .= "par5: ".  $_SESSION['el.elfacility_id'] . nl2br("\n");
echo ( $msg );*/

/* @var $this yii\web\View */
/* @var $model frontend\models\Facility */
/* @var $form yii\widgets\ActiveForm */

    $model->fastreettype = $model->fastreet->streettype;
?>

<div class="facility-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="facility-form">

        <div class="form-group">

            <?= $form->field($model, 'fabuildingno',[
                    'template' => '<div class=mclass2><label> <span>Номер дома</span>{input}</label>{error}</div>',
            ])->textInput(['style'=>'width:300px','maxlength' => true]) ?> 


            <?= $form->field($model, 'fadistrict_id',[
                'template' => '<div class=mclass2><label> <span>Район</span>{input}</label>{error}</div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                        District::find()->select(['id', 'districtname'])->orderby('districtname')->where(['districtlocality_id' => 159])->all(), 'id', 'districtname'),
                        [
                            $model->fadistrict_id => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px'
                        ] )
            ?>

            
            <!--?=  $form->field($model, 'fastreettype',[
                'template' => '<div class=mclass2> <label> <span>Тип улицы</span>{input}</label>{error}</div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                        Street::find()->orderBy('streettype')->all(), 'streettype', 'streettype'), 
                        [
                            $model->fastreettype => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px',
                        ])
            ?-->


            <!--?= $form->field($model, 'fastreet_id',[
                'template' => '<div class=mclass2> <label> <span>Улица</span>{input}</label>{error}</div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                        Street::find()->where(['streetlocality_id' => 159])->all(), 'id', 'streetnameru'),
                        [
                            $model->fastreet_id => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px',
                        ])
            ?-->

            <?= $form->field($model, 'fastreet_id',[
                'template' => '<div class=mclass2> <label> <span>Улица</span>{input}</label>{error}</div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                        Street::find()->select(['id','streetnameru','streettype'])->orderBy('streetnameru')->where(['streetlocality_id' => 159])->all(), 
                            'id', 
                            function($model) {
                                return $model['streettype'].' '.$model['streetnameru'];
                            }
                        ),
                        [
                            $model->fastreet_id => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px',
                        ])
            ?>

            <?= $form->field($model, 'facodesvc',[
                'template' => '<div class=mclass2> 
                <label> <span>Код ЖКХ</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) ?> 

            <?= $form->field($model, 'facode', [
                'template' => '<div class=mclass2> 
                <label> <span>Глобальный код</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) ?> 

            <?= $form->field($model, 'fainventoryno', [
                'template' => '<div class=mclass2> 
                <label> <span>Инвернтарный номер</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) ?> 
      
            <?= $form->field($model, 'fastoreysnum', [
                'template' => '<div class=mclass2> 
                <label> <span>Количество этажей</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) ?>  

            <?= $form->field($model, 'faporchesnum', [
                'template' => '<div class=mclass2> 
                <label> <span>Количество подъездов</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) ?> 
            
            <?= $form->field($model, 'falatitude', [
                'template' => '<div class=mclass2> 
                <label> <span>Географическая широта</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) ?> 

            <?= $form->field($model, 'falongitude', [
                'template' => '<div class=mclass2> 
                <label> <span>Географическая долгота</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) ?> 

        </div>
 
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>&nbsp;&nbsp;
            <?= Html::a('Назад к списку домов', ['facility/index',
                'FacilitySearch[fadistrict_id]' => $_SESSION['fa.fadistrict_id'],
                'FacilitySearch[fastreettype]'  => $_SESSION['fa.fastreettype'],
                'FacilitySearch[fastreetname]'  => $_SESSION['fa.fastreetname'],
                'FacilitySearch[fabuildingno]'  => $_SESSION['fa.fabuildingno'],
                'FacilitySearch[elfacility]'    => $_SESSION['fa.elfacility'] ], ['class'=>'btn btn-success']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>