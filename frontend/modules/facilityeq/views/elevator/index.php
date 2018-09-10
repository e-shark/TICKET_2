<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Url;

use frontend\modules\facilityeq\models\Street;
use frontend\modules\facilityeq\models\District;
use frontend\modules\facilityeq\models\Facility;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ElevatorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

session_start();

/*if (isset($_SESSION['el'])) {
        $_SESSION['el.eldevicetype']  = '';
        $_SESSION['el.eldistrict']    = '';
        $_SESSION['el.elstreettype']  = '';
        $_SESSION['el.elstreetname']  = '';
        $_SESSION['el.elfacility_id'] = '';
}//else {
//$_SESSION['counter']++;
//} 

$msg =  "par1: ".  $_SESSION['el.eldevicetype'] . nl2br("\n");
$msg .= "par2: ".  $_SESSION['el.eldistrict'] . nl2br("\n");
$msg .= "par3: ".  $_SESSION['el.elstreettype'] . nl2br("\n");
$msg .= "par4: ".  $_SESSION['el.elstreetname'] . nl2br("\n");
$msg .= "par5: ".  $_SESSION['el.elfacility_id'] . nl2br("\n");
echo ( $msg );*/

    $this->title = 'Справочники'; 
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
    $this->title ='Список оборудования';
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['elevator/index']];



    $districtTypes = District::find()->select(['id','districtname'])
        ->where(['districtlocality_id' => 159])
        ->orderBy('districtname')
        ->asArray()->all(); 
    $districtTypeList = ArrayHelper::map($districtTypes, 'id', 'districtname'); 
    
  
    $streetTypes = $model->getMyStreetList($searchModel->eldistrict);
    
    $streetTypeList = ArrayHelper::map(
            $streetTypes->select(['streettype'])
            ->orderBy('streettype')->distinct()
            ->asArray()->all(),
             'streettype', 'streettype');
    
    if ($searchModel->elstreettype!=""){
        $streetNameList = ArrayHelper::map(
            $streetTypes->select(['street.id','street.streetnameru'])
            ->andwhere(['like','streettype',$searchModel->elstreettype])
            ->orderBy('streetnameru')->distinct()
            ->asArray()->all(),
             'id', 'streetnameru'); 
    }
    else {
        $streetNameList = ArrayHelper::map(
            $streetTypes->select(['street.id','street.streetnameru'])
            ->orderBy('streetnameru')->distinct()
            ->asArray()->all(),
            'id', 'streetnameru');         
    }

    if ($searchModel->elstreetname!=""){
        $buildingList = ArrayHelper::map( 
            $streetTypes->select(['facility.id', 'facility.fabuildingno'])
            ->andwhere(['fastreet_id' => $searchModel->elstreetname])
            ->orderBy('facility.fabuildingno')->distinct()
            ->asArray()->all(), 
            'id', 'fabuildingno');            
    }
    else {
        $buildingList = ArrayHelper::map( 
            $streetTypes->select(['facility.id', 'facility.fabuildingno'])
            ->orderBy('facility.fabuildingno')->distinct()
            ->asArray()->all(), 
            'id', 'fabuildingno');        
    }
?>


<div class="street-index">

 

        <?= Html::beginForm(['send'],'post'); ?> 
        <!--?php $form = ActiveForm::begin(['id'=>'formarrr', 'method'=>"post"]); ?-->    
        <div class="form-group">
        
            <h1><?= Html::encode($this->title) ?><br></h1>

            <h4>  
                <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?><br></p>  

                <p>Тип оборудования: 
                <?= Html::dropDownList('eldevicetype',$searchModel->eldevicetype, 
                    [
                        '1'=>'Лифты','10'=>'ЭЩ','20'=>'Домофон',
                    ],                                    
                    [
                        'prompt'=>'Все',
                        'onchange'=>'this.form.submit()' ,
                        'style'=>'width:100px'
                    ]                                    
                    
                );?> &nbsp;&nbsp;</p>
                <!--p>Район: <?php// echo '__' . Yii::$app->request->post('eldistrict', null);  ?> </p-->
                <p>Район: 
                <?= Html::dropDownList('eldistrict', $searchModel->eldistrict, $districtTypeList,                      
                    [
                        'prompt'=>'Все',
                        'style'=>'width:200px',
                        'onchange'=>'this.form.submit()' 
                    ]
                );?>&nbsp;&nbsp;

                Тип улицы: 
                <?= Html::dropDownList('elstreettype',$searchModel->elstreettype, $streetTypeList, 
                    [
                        'prompt'=>'Все',
                        'style'=>'width:70px',
                        'onchange'=>'this.form.submit()' 
                    ]
                );?> &nbsp;&nbsp;

                Улица: 
                <?= Html::dropDownList('elstreetname', $searchModel->elstreetname, $streetNameList,  
                    [
                        'prompt'=>'Все',
                        'style'=>'width:250px',
                        'onchange'=>'this.form.submit()' 
                    ]
                );?> &nbsp;&nbsp;

                Дом: 
                <?= Html::dropDownList('elfacility_id', $searchModel->elfacility_id, $buildingList , 
                    [
                        'prompt'=>'Все',
                        'style'=>'width:150px',
                        'onchange'=>'this.form.submit()' 
                    ]
                );?> &nbsp;&nbsp;</p>
        
                <p> 
                    <!--?= Html::a('Применить', ['send', 'params'=>array('eldistrict' => $eldistrict, 'elstreettyp' => $elstreettyp) ], ['class' => 'btn btn-primary']) ?-->
                    <?php //echo Html::submitButton('Применить', ['class' => 'btn btn-primary']); ?> &nbsp;&nbsp;
                    <?= Html::a('Сбросить', ['send',                 
                        'ElevatorSearch[eldevicetype]'  => '',
                        'ElevatorSearch[eldistrict]'    => '',
                        'ElevatorSearch[elstreettype]'  => '',
                        'ElevatorSearch[elstreetname]'  => '',
                        'ElevatorSearch[elfacility_id]' => ''], ['class'=>'btn btn-primary']) ?>
                </p>

            </h4>
            <?= Html::endForm(); ?> 
        </div>



    <div class="form-group">
        
       <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
        'emptyText' => 'Результатов не найдено.',
        'summary' => "Показано {begin} - {end} из {totalCount} элементов",
        'columns' => [
            [
                'attribute' => 'elregion',
                'format' => 'raw',
                'label'  => 'Район',
                'value'=>function ($model){
                    return $model->regionName;
                },
            ],

            [
                'attribute' => 'myattr',
                'format' => 'raw',
                'label'  => 'Улица',
                'value'=>function ($model){
                    return $model->myAddrName;
                },
            ],

            [
                'format' => 'raw',
                'label'  => 'Дом',
                'value'=>function ($model){
                    return $model->myBuildingName;
                },
            ],

            [
                'attribute' => 'elporchno',
                'format' => 'raw',
                'label'  => 'Подъезд',
            ],

            [
                'label'  => 'Установленное оборудование',
                'format' => 'raw',
                'value'=>function ($model){
                    $arr=$model->getCountAll($model->id);
                    $arrELlist  = ArrayHelper::map($arr,'id','countEL');
                    $arrSWlist  = ArrayHelper::map($arr,'id','countSW');
                    $arrBUZlist  = ArrayHelper::map($arr,'id','countBUZ');
                    $result = '';
               

                    ($arrELlist[$model->id] !=0) ?
                        $result .=  Html::a(/*'всего ' . $arrELlist[$model->id] . ' шт., ' .*/ 'Лифт инв. №' . $model->elinventoryno . ', '. $model->eltype . ', ' . $model->elporchpos  ,['elevator/view','id'=>$model->id]). nl2br("\n") :
                        $result .= '';
                    ($arrSWlist[$model->id] !=0) ?
                        $result .=  Html::a(/*'всего ' . $arrSWlist[$model->id] . ' шт., ' . */"ЭЩ инв. №" . $model->elinventoryno, ['elevator/view','id'=>$model->id]). nl2br("\n") :
                        $result .= '';   
                    ($arrBUZlist[$model->id] !=0) ?
                        $result .=  Html::a(/*'всего ' . $arrBUZlist[$model->id] . ' шт., '  .*/ "Домофон инв. №" . $model->elinventoryno ,['elevator/view','id'=>$model->id]). nl2br("\n") :
                        $result .= '';   


                    return $result;
                }
            ],
            //['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],

    ]); ?>


</div>
