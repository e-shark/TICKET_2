<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Breadcrumbs;
    use yii\helpers\ArrayHelper;

    use frontend\modules\facilityeq\models\Street;
    use frontend\modules\facilityeq\models\District;

    session_start();

    /*$msg =  "par1: ".  $_SESSION['el.eldevicetype'] . nl2br("\n");
    $msg .= "par2: ".  $_SESSION['el.eldistrict'] . nl2br("\n");
    $msg .= "par3: ".  $_SESSION['el.elstreettype'] . nl2br("\n");
    $msg .= "par4: ".  $_SESSION['el.elstreetname'] . nl2br("\n");
    $msg .= "par5: ".  $_SESSION['el.elfacility_id'] . nl2br("\n");
    echo ( $msg );*/

    /* @var $this yii\web\View */
    /* @var $searchModel frontend\models\StreetSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    $this->title = 'Справочники';   
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['default/index']];
    $this->title ='Список улиц';
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['street/index']];
   
   //echo Url::toRoute('send');
   //echo Yii::$app->getRequest()->getUrl();

    $districtTypes = District::find()->select('districtname')->where(['districtlocality_id' => 159])->orderBy('districtname')->asArray()->all(); 
    $districtTypeList = ArrayHelper::map($districtTypes, 'districtname', 'districtname');
    
    $streetTypes = $model->getMyStreetList($searchModel->streetdistrict);
    $streetTypeList = ArrayHelper::map($streetTypes->orderBy('streettype')->asArray()->all(), 'streettype', 'streettype');
    
    if ($searchModel->streettype!=""){
        $streetNameList = ArrayHelper::map($streetTypes->andwhere(['like','streettype',$searchModel->streettype])->orderBy('streetnameru')->asArray()->all(), 'streetnameru', 'streetnameru'); 
    }
    else {
        $streetNameList = ArrayHelper::map($streetTypes->orderBy('streetnameru')->asArray()->all(), 'streetnameru', 'streetnameru');         
    }    
?>


<div class="street-index">
    
    <div class="form-group">
        <?= Html::beginForm(['send'],'post'); ?> 
            <h1><?= Html::encode($this->title) ?><br></h1>
            <h4>  
                <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?><br></p>  

                <p>Район: 
                <?= Html::dropDownList('streetdistrict',$searchModel->streetdistrict, $districtTypeList,
                     [
                        'prompt'=>'Все',
                        'onchange'=>'this.form.submit()' 
                     ]
                );  
                ?> &nbsp;&nbsp;

                Тип улицы: 
                <?= Html::dropDownList('streettype',$searchModel->streettype, $streetTypeList, 
                     [
                        'prompt'=>'Все',
                        'onchange'=>'this.form.submit()' 
                     ]
                );  
                ?> &nbsp;&nbsp;

                Улица: 
                <?= Html::dropDownList('streetnameru',$searchModel->streetnameru, $streetNameList,  
                     [
                        'prompt'=>'Все',
                        'onchange'=>'this.form.submit()' 
                     ]
                );  
                ?> &nbsp;&nbsp;</p>
                
                <p> 
                    <!--?= Html::a('Применить', ['send', 'params'=>array('eldistrict' => $eldistrict, 'elstreettyp' => $elstreettyp) ], ['class' => 'btn btn-primary']) ?-->
                    <?php //echo Html::submitButton('Применить', ['class' => 'btn btn-primary']); ?> &nbsp;&nbsp;
                    <?= Html::a('Сбросить', ['send', 
                        'StreetSearch[streetdistrict]'  => '',
                        'StreetSearch[streettype]'      => '',
                        'StreetSearch[streetnameru]'    => ''], ['class'=>'btn btn-primary']) ?>
                </p>
            </h4>
    </div>

    <?= Html::endForm(); ?> 

    <div id="view-mode-pjax">        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '(не задано)'],
            'emptyText' => 'Результатов не найдено.',
            'summary' => "Показано {begin} - {end} из {totalCount} элементов",
            'columns' => [
                [
                    'attribute' => 'streetnameru',
                    'format' => 'raw',
                    'label'  => 'Наименование',
                    'value'=>function ($model){
                        return Html::a($model->streettype . ' ' . $model->streetnameru,['street/view','id'=>$model->id]);
                    }
                ],

                [
                    'attribute' => 'streetdistrict',
                    'format' => 'raw',
                    'label'  => 'Район',
                ],
            
                [
                    'attribute' => 'streettype',
                    'format' => 'raw',
                    'label'  => 'Кол. домов',
                    'value'=>function ($model) {
                        return $model->getFacilities()->count('id');// $model->countfacilities;
                    }
                ],

            ],
        ]);?>
    </div>

</div>
