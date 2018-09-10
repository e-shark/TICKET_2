<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;

    use frontend\modules\facilityeq\models\District;
    use frontend\modules\facilityeq\models\Street;
    use frontend\modules\facilityeq\models\Facility;    

    session_start();

    /*$msg =  "par1: ".  $_SESSION['fa.fadistrict_id'] . nl2br("\n");
    $msg .= "par2: ".  $_SESSION['fa.fastreettype'] . nl2br("\n");
    $msg .= "par3: ".  $_SESSION['fa.fastreetname'] . nl2br("\n");
    $msg .= "par4: ".  $_SESSION['fa.fabuildingno'] . nl2br("\n");
    $msg .= "par5: ".  $_SESSION['fa.elfacility'] . nl2br("\n");
    echo ( $msg );
    /* @var $this yii\web\View */
    /* @var $searchModel frontend\models\FacilitySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Справочники'; 
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
    $this->title = 'Список домов';
    $this->params['breadcrumbs'][] = $this->title;

    $districtTypes = District::find()
        ->where(['districtlocality_id' => 159])
        ->orderBy('districtname')
        ->asArray()->all(); 
    $districtTypeList = ArrayHelper::map($districtTypes, 'id', 'districtname'); 
    
   
    $streetTypes = $model->getMyStreetList($searchModel->fadistrict_id);
    
    $streetTypeList = ArrayHelper::map(
            $streetTypes->select('streettype')
            ->orderBy('streettype')->distinct()
            ->asArray()->all(),
             'streettype', 'streettype');
    
    if ($searchModel->fastreettype!=""){
        $streetNameList = ArrayHelper::map(
            $streetTypes->select(['street.id','street.streetnameru'])->andwhere(['like','streettype',$searchModel->fastreettype])
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

    if ($searchModel->fastreetname!=""){
        $buildingList = ArrayHelper::map( 
            $streetTypes->select('facility.fabuildingno')
            ->andwhere(['fastreet_id' => $searchModel->fastreetname])
            ->orderBy(['fabuildingno' => SORT_ASC])->distinct()
            ->asArray()->all(), 
            'fabuildingno', 'fabuildingno');            
    }
    else {
        $buildingList = ArrayHelper::map( 
            $streetTypes->select('facility.fabuildingno')
            ->orderBy(['fabuildingno' => SORT_ASC])->distinct()
            ->asArray()->all(), 
            'fabuildingno', 'fabuildingno');        
    }
  

?>

<div class="street-index">    
    <div class="form-group">
        <?= Html::beginForm(['send'],'post') ?>        
        
        <h1><?= Html::encode($this->title) ?><br></h1>
        
        <h4>  
            <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?><br></p>            

            <p>Район:             
            <?= Html::dropDownList('fadistrict_id', $searchModel->fadistrict_id, $districtTypeList, 
                [
                    'prompt'=>'Все',
                    'style'=>'width:200px',
                    'onchange'=>'this.form.submit()' 
                ]
            );  
            ?> &nbsp;&nbsp;

            Тип улицы: 
            <?= Html::dropDownList('fastreettype', $searchModel->fastreettype, $streetTypeList, 
                [
                    'prompt'=>'Все',
                    'style'=>'width:100px',
                    'onchange'=>'this.form.submit()' 
                ]
            );  
            ?> &nbsp;&nbsp;

            Улица: 
            <?= Html::dropDownList('fastreetname', $searchModel->fastreetname, $streetNameList,  
                [
                    'prompt'=>'Все',
                    'style'=>'width:250px',
                    'onchange'=>'this.form.submit()' 
                ]
            );  
            ?> &nbsp;&nbsp;

            Дом: 
            <?= Html::dropDownList('fabuildingno', $searchModel->fabuildingno, $buildingList, 
                [
                    'prompt'=>'Все',
                    'style'=>'width:150px',
                    'onchange'=>'this.form.submit()' 
                ]
            );
            ?> &nbsp;&nbsp;</p>

            <p>Оборудование: 
                <?= Html::dropDownList('elfacility', $searchModel->elfacility,
                    [
                        '1'=>'Лифт','10'=>'ЭЩ','20'=>'Домофон'
                    ], 
                    [
                        'prompt'=>'Все',
                        'style'=>'width:100px',
                        'onchange'=>'this.form.submit()' 
                    ]
                );?> &nbsp;&nbsp;
            </p>
        
            <p> 
                <!--?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?--> &nbsp;&nbsp;
                <?= Html::a('Сбросить', ['send', 
                    'FacilitySearch[fadistrict_id]' => '',
                    'FacilitySearch[fastreettype]'  => '',
                    'FacilitySearch[fastreetname]'  => '',
                    'FacilitySearch[fabuildingno]'  => '',
                    'FacilitySearch[elfacility]'    => '' ],  ['class'=>'btn btn-primary']) ?>
            </p>

        </h4>
        <?= Html::endForm(); ?> 
    </div>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
        'emptyText' => 'Результатов не найдено.',
        'summary' => "Показано {begin} - {end} из {totalCount} элементов",
        'columns' => [

            [
                'attribute' => 'facodesvc',
                'label'  => 'Код',
            ],

            [
                'label'  => 'Улица',
                'value'=>function ($model){
                        return $model->fastreet->streettype.' '. $model->fastreet->streetnameru;
                    }
            ],

            [
                'attribute' => 'fabuildingno',
                'label'  => 'Дом',
                'format' => 'raw',
                'value'=>function ($model){
                        return Html::a($model->fabuildingno,['facility/view','id'=>$model->id]);                        
                    },
            ],

            [
                'attribute' => 'faporchesnum',
                'label'  => 'Кол. подъездов',
            ],

            [
                'label'  => 'Установленное оборудование',
                'format' => 'raw',
                'value'=>function ($model)  {
                        $arr=$model->getCountAll($model->id);
                        $arrELlist  = ArrayHelper::map($arr,'id','countEL');
                        $arrSWlist = ArrayHelper::map($arr,'id','countSW');
                        $arrBUZlist = ArrayHelper::map($arr,'id','countBUZ');

                        $result = '';

                        ($arrELlist[$model->id] !=0) ?
                             $result .= Html::a("Лифты " . $arrELlist[$model->id] . " шт.",['elevator/index','ElevatorSearch[eldevicetype]'=>1,'ElevatorSearch[elfacility_id]'=>$model->id]). nl2br("\n") :
                             $result .= Html::a("Лифты " .  $arrELlist[$model->id] . " шт.",['elevator/create']). nl2br("\n");
                        ($arrSWlist[$model->id] !=0) ?
                             $result .= Html::a("ЭЩ " . $arrSWlist[$model->id]  . " шт.",['elevator/index','ElevatorSearch[eldevicetype]'=>10,'ElevatorSearch[elfacility_id]'=>$model->id]). nl2br("\n") :
                             $result .= Html::a("ЭЩ " . $arrSWlist[$model->id]  . " шт.",['elevator/create']). nl2br("\n");     
                        ($arrBUZlist[$model->id] !=0) ?
                             $result .= Html::a("Домофоны " . $arrBUZlist[$model->id]. " шт.",['elevator/index','ElevatorSearch[eldevicetype]'=>20,'ElevatorSearch[elfacility_id]'=>$model->id]). nl2br("\n") :
                             $result .= Html::a("Домофоны " . $arrBUZlist[$model->id] . " шт.",['elevator/create']);                             

                        return $result;
                    }
            ],


        ],
    ]); ?>


    
</div>
