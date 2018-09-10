<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

use frontend\modules\facilityeq\models\Companyform;

/* @var $this yii\web\View */
/* @var $model frontend\modules\facilityeq\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--?= $form->field($model, 'companyname')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyname', [
        'template' => '<div class=mclass2> <label> <span>Краткое наименование</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>

    <!--?= $form->field($model, 'companyfullname')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyfullname', [
        'template' => '<div class=mclass2> <label> <span>Полное наименование</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>

    <!--?= $form->field($model, 'companynameeng')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companynameeng', [
        'template' => '<div class=mclass2> <label> <span>Наименование на английском</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>

    <!--?= $form->field($model, 'companycode')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companycode', [
        'template' => '<div class=mclass2> <label> <span>Код</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>

    <!--?= $form->field($model, 'companytaxcode')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companytaxcode', [
        'template' => '<div class=mclass2> <label> <span>Номер свид. налогоплательщика</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companydate')->textInput() ?-->
    <!--?= $form->field($model, 'companydate', [
        'template' => '<div class=mclass2> <label> <span>Дата</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?-->

    <?= $form->field($model, 'companydate', [
        'template' => '<div class="elevator-form"> <label> <span>Дата</span> {input} </label> {error} </div>',
        ])->textInput(['style'=>'width:300px','maxlength' => true])->widget(Datepicker::className(),
            [ 
                'language' => 'ru-RU',
                'dateFormat' => 'dd-MM-yyyy',
                'options'=>[
                    'style'=>'width:600px;',
                    'class'=>'form-control',
                ]
            ]
        )
    ?>
    
    <!--?= $form->field($model, 'companyphone')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyphone', [
        'template' => '<div class=mclass2> <label> <span>Телефон</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companyfax')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyfax', [
        'template' => '<div class=mclass2> <label> <span>Факс</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>

    <!--?= $form->field($model, 'companyemail')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyemail', [
        'template' => '<div class=mclass2> <label> <span>Электронная почта</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companyurl')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyurl', [
        'template' => '<div class=mclass2> <label> <span>Сайт</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companyzip')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyzip', [
        'template' => '<div class=mclass2> <label> <span>Индекс</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companyaddress')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyaddress', [
        'template' => '<div class=mclass2> <label> <span>Адрес</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companyrole')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companyrole', [
        'template' => '<div class=mclass2> <label> <span>Роль</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companydescription')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'companydescription', [
        'template' => '<div class=mclass2> <label> <span>Описание</span>{input}</label>{error}</div>',
    ])->textInput(['style'=>'width:600px','maxlength' => true]) ?>
    
    <!--?= $form->field($model, 'companyform_id')->textInput() ?-->
    <?= $form->field($model, 'companyform_id', [
        'template' => '<div class=mclass2> <label> <span>Форма собственности</span>{input}</label>{error}</div>',
        ])->dropDownList( 
            ArrayHelper::map(
            Companyform::find()->select(['id','companyformname'])->orderBy('companyformname')->all(), 'id', 'companyformname'), 
                [
                    $model->companyform_id => ['selected' => true],
                    'maxlength' => true,
                    'style'=>'width:600px',
                ]
        )
    ?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>&nbsp;&nbsp;
        <?= Html::a('Назад к списку компаний', ['company/index'], ['class'=>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
