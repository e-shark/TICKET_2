<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use common\widgets\Script;
use yii\helpers\Url;
use yii\widgets\Pjax; 


use frontend\modules\facilityeq\models\District;
use frontend\modules\facilityeq\models\Elevator;
use frontend\modules\facilityeq\models\Street;
use frontend\modules\facilityeq\models\Facility;
use frontend\modules\facilityeq\models\Company;
use frontend\modules\facilityeq\models\Rtu;
use frontend\modules\employeeeq\models\Division;
use frontend\modules\employeeeq\models\Employee;

session_start();

/*$par1 = $_SESSION['dr.fadistrictname'];
$par2 = $_SESSION['dr.fastreetname'];
$par3 ='';

$msg  =  "par1: ".  $par1 . nl2br("\n");
$msg .=  "par2: ".  $par2 . nl2br("\n");
$msg .=  "par3: ".  $par2 . nl2br("\n");

echo ( $msg );*/

$model->eldistrict = $model->elfacility->fadistrict->districtname;
$model->elstreettype = $model->elfacility->fastreet->streettype;
$model->elstreetname = $model->elfacility->fastreet->id;
  
/*(Yii::$app->session->setFlash('success', '_  params:' . nl2br("\n") . 
   '1: ' . $model->eldistrict . nl2br("\n") . 
   '2: ' . $model->elstreettype . nl2br("\n") .  
   '3: ' . $model->elstreetname . nl2br("\n") .
   '4: ' . $model->elfacility_id . nl2br("\n") .
   '5: ' . $model->elfacility->fadistrict->districtname . nl2br("\n") .  
   '6: ' . $model->elfacility->fastreet->streettype . nl2br("\n") .  
   '7: ' . $model->elfacility->fastreet->id. nl2br("\n") .
   '8: ' . $model->myBuildingName
   );*/

    $districtNameList = ArrayHelper::map(
                    District::find()->select('districtname')
                        ->where(['districtlocality_id' => 159])
                        ->orderBy('districtname')->all(),
                         'districtname', 
                            function($model) {
                                session_start();
                                return $model['districtname'];
                            });

    $streetNameList = $model->getStreetList($model->eldistrict);


    $buildingList = ArrayHelper::map( $model->getCallTypesList($model->elstreetname)
                        ->select(['facility.id as fid','facility.fabuildingno as fbld'])
                        ->orderBy('facility.fabuildingno')->distinct()
                        ->asArray()->all(),
                        'fid', 'fbld');
    
?>

 


<div class="elevator-form">


    <?php $form = ActiveForm::begin(
                [
                    'method' => 'POST', 
                    'action' => [
                        //'senddrop', 
                        'id'=> $model->id
                        ]
                ]); 
    ?>

    <div class="panel panel-default">  

        <div class="panel-heading"><h3>Регистрационные данные</h3></div>

        <div class="panel panel-body"> 

            <?=  $form->field($model, 'eldevicetype',[
                'template' => '<div class="elevator-form"> <label> <span>Тип оборудования</span> {input} </label> {error} </div>',
                ])->dropDownList( ['1'=>'Лифт','10'=>'ЭЩ','11'=>'Домофон'],
                    [   
                        'language' => 'ru-RU',
                        'maxlength' => true,
                        'style'=>'width:300px',
                    ]
                ) 
            ?>
           
            <?= $form->field($model, 'elinventoryno', [
                'template' => '<div class="elevator-form"> <label> <span>Инвентарный номер</span> {input} </label> {error} </div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true])
            ?>

            <?= $form->field($model, 'eldate', [
                'template' => '<div class="elevator-form"> <label> <span>Дата регистрации</span> {input} </label> {error} </div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true])->widget(Datepicker::className(),
                    [ 
                        'language' => 'ru-RU',
                        'dateFormat' => 'dd-MM-yyyy',
                        'options'=>[
                            'style'=>'width:300px;',
                            'class'=>'form-control',
                        ]
                    ]
                )
            ?>

            <?php                                        
                echo  $form->field($model, 'eldistrict', [
                'template' => '<div class="elevator-form"> <label> <span>Район</span> {input} </label> {error} </div>',
                ])->dropDownList($districtNameList,
                    [
                        $model->eldistrict => ['selected' => true],
                        'maxlength' => true,
                        'style'=>'width:300px',
                        'id' => 'region-selected',
                        //'onclick'=>'
                        'onchange'=>'
                            $.get( "'.Url::toRoute('elevator/streetlists').'", { id: $(this).val() } )
                            .done(function( data )
                            {
                                $( "select#street-selector" ).html( data );
                            });'
                    ]
                );

                echo  $form->field($model, 'elstreetname',[
                'template' => '<div class=mclass2> <label> <span>Улица</span>{input}</label>{error}</div>',
                ])->dropDownList($streetNameList,
                        [
                            $model->elstreetname => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px', 
                            'id' => 'street-selector',
                            'onchange'=>'
                                $.get( "'.Url::toRoute('elevator/buildlists').'", { id: $(this).val() } )
                                .done(function( data )
                                {
                                    $( "select#build-selector" ).html( data );
                                });'
                        ]
                );

                echo $form->field($model, 'elfacility_id',[
                'template' => '<div class="elevator-form"> <label> <span>Дом</span> {input} </label> {error} </div>',
                ])->dropDownList($buildingList,
                    [
                        $model->elfacility_id => ['selected' => true],
                        'maxlength' => true,
                        'style'=>'width:300px',
                        'id' => 'build-selector'                        
                    ]
                );
           ?>

 
            <?= $form->field($model, 'elporchno', [
                'template' => '<div class="elevator-form"> <label> <span>Подъезд</span> {input} </label> {error} </div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) 
            ?>

            <?=  $form->field($model, 'elporchpos',[
                'template' => '<div class="elevator-form"> <label> <span>Положение в подъезде</span> {input} </label> {error} </div>',
                ])->dropDownList( ['лв'=>'лв','пр'=>'пр'],
                    [ 
                        $model->elporchpos => ['selected' => true], 
                        'prompt'=>'-', 
                        'maxlength' => true,
                        'style'=>'width:300px',
                    ]    
                )
            ?>
    
            <?= $form->field($model, 'eldivision_id', [
                'template' => '<div class="elevator-form"> <label> <span>Обслуживающее подразделение</span> {input} </label> {error} </div>',
                ])->dropDownList( 
                ArrayHelper::map(
                    Division::find()->select(['id', 'divisionname'])->orderBy('divisionname')->all(), 'id', 'divisionname'),
                    [
                        $model->eldivision_id => ['selected' => true],
                        'maxlength' => true,
                        'style'=>'width:300px',
                    ]
                ) 
            ?>

            <?= $form->field($model, 'elperson_id', [
                'template' => '<div class="elevator-form"> <label> <span>Закрепленный электромеханик</span> {input} </label> {error} </div>',
                ])->dropDownList( 
                ArrayHelper::map(
                    Employee::find()->select(['id', 'lastname','firstname','patronymic'])->orderBy('lastname')->all(), 
                            'id', 
                            function($model) {
                                return $model['lastname'].' '.$model['firstname'].' '.$model['patronymic'];
                            }
                            ),
                    [
                        $model->eldivision_id => ['selected' => true],
                        'maxlength' => true,
                        'style'=>'width:300px',
                    ]
                ) 
            ?>

        </div>

    </div>


    <div class="panel panel-default">  

        <div class="panel-heading"><h3>Принадлежность оборудования</h3></div>

        <div class="panel panel-body">         

            <?= $form->field($model, 'elownercompany_id', [
                'template' => '<div class="elevator-form"> <label> <span>Собственник</span> {input} </label> {error} </div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                        Company::find()->select(['id', 'companyname'])->orderBy('companyname')->all(), 'id', 'companyname'), 
                        [
                            $model->elownercompany_id => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px',
                        ]
                )
            ?>

            <?= $form->field($model, 'elservicecompany_id', [
                'template' => '<div class="elevator-form"> <label> <span>Исполнитель</span> {input} </label> {error} </div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                        Company::find()->select(['id', 'companyname'])->orderBy('companyname')->all(), 'id', 'companyname'), 
                        [
                            $model->elservicecompany_id => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px',
                        ]
                )
            ?>

            <?= $form->field($model, 'elsubservicecompany_id', [
                'template' => '<div class="elevator-form"> <label> <span>Субподрядчик</span> {input} </label> {error} </div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                        Company::find()->select(['id', 'companyname'])->orderBy('companyname')->all(), 'id', 'companyname'), 
                        [
                            $model->elsubservicecompany_id => ['selected' => true],
                            'maxlength' => true,
                            'style'=>'width:300px',
                        ]
                )
            ?>

        </div>

    </div>


    <div class="panel panel-default">  

        <div class="panel-heading"><h3>Системные настройки</h3></div>
    
        <div class="panel panel-body">

            <?= $form->field($model, 'elrtu_id', [
                'template' => '<div class="elevator-form"> <label> <span>Устройство диспетчеризации</span>{input}</label>{error}</div>',
                ])->dropDownList( 
                    ArrayHelper::map(
                    Rtu::find()->select(['id', 'rtumodel','rtuphone','rtuserialno'])->orderBy('rtumodel')->all(), 
                            'id', 
                            function($model) {
                                $returnval =  'Модель: ' . $model['rtumodel'] . " \n " . 'Телефон: '     . $model['rtuphone'] . " \n " .   'Серийный №: '  . $model['rtuserialno'];
                                return $returnval;
                            }
                            ),
                    [
                        $model->elrtu_id=> ['selected' => true],
                        'maxlength' => true,
                        'style'=>'width:300px',
                    ]
                ) 
            ?>
        
            <?= $form->field($model, 'elremoteid', [
                'template' => '<div class="elevator-form"> <label> <span>Номер в смежной системе</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true])
            ?>  

        </div>

    </div> 

    <div class="panel panel-default">  

        <div class="panel-heading"><h3>Паспортные данные</h3></div>
    
        <div class="panel panel-body">
    

            <?= $form->field($model, 'elserialno', [
                'template' => '<div class="elevator-form"> <label> <span>Заводской номер</span> {input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true])
            ?>


            <?= $form->field($model, 'elmodel', [
                'template' => '<div class="elevator-form"> <label> <span>Модель</span> {input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) 
            ?>

            <?= $form->field($model, 'elload', [
                'template' => '<div class="elevator-form"> <label> <span>Грузоподъемность</span> {input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) 
            ?>

            <?= $form->field($model, 'elspeed', [
                'template' => '<div class="elevator-form"> <label> <span>Скорость</span>{input}</label>{error}</div>',
                ])->textInput(['style'=>'width:300px','maxlength' => true]) 
            ?>

        
            <?=  $form->field($model, 'eldoortype',[
                'template' => '<div class="elevator-form"> <label> <span>Тип двери</span>{input}</label>{error}</div>',]
                )->dropDownList( ['Розсувні'=>'Розсувні','Розпашні'=>'Розпашні'],
                    [   
                        'maxlength' => true,
                        'style'=>'width:300px',
                        $model->eldoortype => ['selected' => true],
                    ]    
                )
            ?>

            <?=  $form->field($model, 'eltype',[
                'template' => '<div class="elevator-form"> <label> <span>Тип</span>{input}</label>{error}</div>',]
                )->dropDownList( ['пас'=>'пас','вант-пас'=>'вант-пас'],
                    [   
                        'maxlength' => true,
                        'style'=>'width:300px',
                        $model->eltype => ['selected' => true],
                    ]    
                )
            ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>&nbsp;&nbsp;
        <?= Html::a('Назад к списку оборудования', ['elevator/index',
                'ElevatorSearch[eldevicetype]'  => $_SESSION['el.eldevicetype'],
                'ElevatorSearch[eldistrict]'    => $_SESSION['el.eldistrict'],
                'ElevatorSearch[elstreettype]'  => $_SESSION['el.elstreettype'],
                'ElevatorSearch[elstreetname]'  => $_SESSION['el.elstreetname'],
                'ElevatorSearch[elfacility_id]' => $_SESSION['el.elfacility_id'] ], ['class'=>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>
