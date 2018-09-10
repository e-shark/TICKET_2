<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use  components\BootstrapMultiselect\BootstrapMultiselectAsset;
use frontend\models\Report_Titotals;
use frontend\models\Report_OOS;
use yii\jui\DatePicker;
use frontend\models\Tickets;
use yii\widgets\Pjax;


$this->title = "Отчет по открытым заявкам на ".($model->object_id==1 ? "лифты": "ВДЭС");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//--- Format page for printing
$print_script = <<<JS
@media print { 
    h1,.wrap>.container{margin:0;padding:0;}
    .footer, .breadcrumb, .pagination, 
    button#submitFltr1, input#printButton, div#paramsfilter1, #divvisibility_id { display: none; } 
    .report-holder a[href]::after {content: "";}
     div.report-holder *:not(h1){font-size:12px}

    .clearfix:after {
    content: "";
    display: table;
    clear: both;
    }
}
@media screen {  div#paramsfilter2 { display: none; } } 

.left {float:left;}  
JS;
$this->registerCss( $print_script);
$this->registerJs( 'function print_page(){window.print() ;}', yii\web\View::POS_HEAD);

 ?>

<div class="report-holder">
	<h1><?= Html::encode($this->title) ?></h1>
	 
<?php
  // ****** Print filter values*****
 echo "<div id='paramsfilter2'>";
 if ($model->district=='') $Districts='Все'; 
 else
   $Districts = implode(', ', $model->district);
 echo "<div class='left'>Районы: $Districts &nbsp &nbsp &nbsp &nbsp </div>";
 echo "<div class='left' > Период : с $model->datefrom по  $model->dateto&nbsp</div>";
echo '<div class="clearfix">  </div> <br>';
 echo "</div>";


  //----Screen filter Form
  echo "<div id='paramsfilter1'>"; 
  echo '<p>';
  //$url = $this->context->getRoute();
//  $url = Url::toRoute(['Oosnow', 'object_id' =>$model->object_id]);
  echo Html::beginForm([/*'titotals'*/$this->context->getRoute(),'object_id' =>$model->object_id],'get',['class'=>'form','id'=>'formFltr1']);
  echo '<div class="row">';

  echo '<div class="form-group col-sm-2"> Район :&nbsp&nbsp&nbsp&nbsp'.
   Html::dropDownList('district', $model->district,  Report_Oos::getDistrictsList(),
        ['class'=>'form-control','id'=>'district_id','multiple'=>'multiple']).'</div>';

        BootstrapMultiselectAsset::register($this);

//             onDeselectAll,onSelectAll checked ? 'selected' : 'deselected'    

$addr2 = Url::toRoute(["get-executantdesk-list"]);   
$multiselectscript = <<<JS
    
    function updateDivisionList(){
        $.ajax({
          url: '$addr2',
          type: "POST",
          dataType: "json",
          data: {object_id:$model->object_id,Districts:  $('#district_id').val()},
          success: function(data) {
             $("#divtivexecutantdesk").html(data);
             },
             error:   function() {
                $("#divtivexecutantdesk").html('AJAX error!');
             }
        });
    }

    function disabeDependantFormControls(disable){
        if (disable){
            $('#submitFltr1').attr('disabled','disabled');
            $('#executantdesk').attr('disabled','disabled');
        }
        else  {
            $('#submitFltr1').removeAttr('disabled');
            $('#executantdesk').removeAttr('disabled');
        }
    }

    $(document).ready(function() {
        $('#district_id').multiselect({
            includeSelectAllOption: true,
            selectAllValue: '0',
            selectAllText: 'Все',
            allSelectedText:'Выбраны все',
            numberDisplayed: 2,
            nSelectedText: 'выбрано',
            nonSelectedText: 'Ничего не выбрано',
            onChange: function(option, checked, select) {
                var is_empty =  $('#district_id').val() == "";
                disabeDependantFormControls(is_empty);
                if(!is_empty)
                    updateDivisionList();
            },
            onSelectAll: function() {
                updateDivisionList();
                disabeDependantFormControls(false);
            },
            onDeselectAll: function() {
                disabeDependantFormControls(true);
            }
        });
    });
JS;
  $this->registerJs($multiselectscript,yii\web\View::POS_LOAD);
  echo '<div class="form-group col-sm-2"> Дата&nbspс :'.
    DatePicker::widget(['name'  => 'datefrom',
                        'value'  => $model->datefrom,
                        'dateFormat' => 'dd-MM-yyyy',
                        'options'=>['class'=>'form-control']]).'</div>';

  echo '<div class="form-group col-sm-2"> Дата&nbspпо :'.
    DatePicker::widget(['name'  => 'dateto',
                        'value'  => $model->dateto,//date('d-M-y'),
                        'dateFormat' => 'dd-MM-yyyy',
                        'options'=>['class'=>'form-control']]).'</div>';

  echo '<div class="form-group col-sm-2" id="divtivexecutantdesk"> Подр.исполнителя:&nbsp'.
    Html::dropDownList('f_tiexecutantdesk', $model->f_tiexecutantdesk,  
        Report_OOS::getDivisionList($model->sqlView, $model->district),
        ['class'=>'form-control', 'id'=>'executantdesk']).'</div>';//Tickets::getMasterDesksList(TRUE)

   echo '<div class="form-group col-sm-1"><br>';
   echo Html::submitButton(Yii::t('app','Choose'),['class'=>'submit btn btn-success','id'=>'submitFltr1']).'</div>';
   echo '</div>'; /* End of row*/
   echo Html::endForm();
   echo '</p>';
  echo '</div>';
?> 

<?php Pjax::begin(['id'=>'OOSpivotGrid']);
      echo "<div> <center>".GridView::widget([
            'summary' => "Всего записей: <b>{totalCount}</b>",
            'dataProvider' => $model->pivotProvider,
            'rowOptions'=> function ($model, $key, $index, $grid){
                $res =[];
                if($model['region']=='Итого')
                    $res = ['style' => 'background-color:#778899;font-weight: bold;'];
                else if ($model['divisionname']=='Всего')
                    $res = ['style' => 'background-color:#BBCCDD;font-weight: bold;'];
                return $res;},
            'columns' => [
                [
                'label' =>"Район",
                'attribute' => 'region',
                ],
                [
                'label' =>"Исполнитель",
                'attribute' => 'divisionname',
                ],
                [
                'label' =>"Всего заявок",
                'attribute' => 'cnt',
                ],
                [
                'label' =>"В том числе 1562",
                'attribute' => 'cnt1562',
                ],
                [
                'label' =>"В том числе ДЖХ",
                'attribute' => 'cntItera',
                ],
                [
                'label' =>"Просроченных",
                'attribute' => 'cntoverdue',
                ],
            ]
        ])."</center></div>";
     Pjax::end();
 ?>

    <?= "<div id='divvisibility_id'>".Html::checkbox("visibility", false, ['label'=>'Скрыть таблицу заявок', 'id'=>'visibility_id','onchange'=>'setGridVisible(this)'])."</div>";?>

<script>
    function setGridVisible(el) {
        var checked = el.checked;
        document.getElementById('w3').hidden = checked; 
        var label = el.parentElement;
        var labelHTML = label.innerHTML; 
        if (checked) {
            labelHTML = labelHTML.replace("Скрыть", "Показать");
            labelHTML = labelHTML.replace("onchange", " checked onchange");
        }
        else {
           labelHTML = labelHTML.replace("Показать", "Скрыть");
           labelHTML = labelHTML.replace(' checked="" onchange', "onchange");
        }
        label.innerHTML = labelHTML;
        return true;
    }
</script>


<?php  
    Pjax::begin(['id'=>'OOSGrid']);
    echo GridView::widget([
    		'dataProvider' => $provider,
    		'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
            	[
                'label' =>"Время инцидента",
                //'format'=>['date','dd-MM-yyyy  HH:m'],
                'attribute' => 'tiincidenttime',
                'content' => function($data){ return date("d-m-Y H:i",strtotime($data['tiincidenttime']));},  
            	],
            	[
                'label' =>"Открыта, часов",
                'attribute' => 'ooshours',
                'contentOptions'=> function($data){ return ['style' => 'min-width: 50px; white-space: normal;'. (strtotime($data['tiplannedtimenew']) < time() ? ' color:red;':'')];},
                ],
            	[
                'label' =>"Инв. Номер",
                'attribute' => 'tiobjectcode',
            	],
            	[
                'label' =>"Адрес",
                'attribute' => 'tiaddress',
                'content'=>function($data){return 
                    ((1==$data['tiobject_id']) ? "<span style='color:#228B22 ' class='glyphicon glyphicon-resize-vertical'>":
                    ((2==$data['tiobject_id']) ? "<span style='color:#FF4500 'class='glyphicon glyphicon-flash'>":
                    ((3==$data['tiobject_id']) ? "<span style='color:#4169E1 'class='glyphicon glyphicon-phone'>":"")))
                    .mb_substr($data['tiregion'],0,3).'.'
                    ."</span>&nbsp".$data['tiaddress'];},
                    'contentOptions' => ['style' => 'min-width: 350px; white-space: normal;'],
            	],
            	[
                'label' =>"Номер заявки",
                'attribute' => 'ticode',
                'format'        =>'html',
                'content' => function($data){ 
                    $colremote = (FALSE!==strpos($data['ticalltype'],"1562"))?'#E9967A':'grey';
                    $cremote = $data['ticoderemote'];
                    $url = Url::toRoute(['tickets/view', 'id' => $data['id']]);
                    return  "<a href=$url>".$data['ticode'].'</a>'.($cremote?" <span class='glyphicon glyphicon-link' style='color:$colremote;vertical-align:super;font-size:80%'></span><br><span style='font-weight:normal;font-size:11px;color:$colremote'>$cremote</span>":'') ;},
            	],
            	[
                'label' =>"Сервисное подразделение",
                'attribute' => 'divisionname',
            	],
                [ 
                    'attribute' => 'tiproblemtypetext',         
                    'label'=>'Неисправность',
                    'format'        =>'html',
                    'content' => function($data){ return 
                        ($data['oostypetext']       ?"<strong style='color:#585858'>".$data['oostypetext']."</strong>":
                            "<strong style='color:red'>ПРИЧИНА НЕ ОПРЕДЕЛЕНА</strong>")."<br>".
                        ($data['tiproblemtypetext'] ?$data['tiproblemtypetext'] ."<br>":"").
                        ($data['tiproblemtext']     ?$data['tiproblemtext']     ."<br>":"").
                        ($data['tidescription']     ?$data['tidescription']     ."<br>":"").
                        ($data['tiresulterrortext'] ?$data['tiresulterrortext'] ."<br>":"");
                    },
                    'contentOptions' => ['style' => 'min-width: 350px; white-space: normal;'],
                ],
            ]
		]);
    Pjax::end();
?>
    <input id="printButton" type="image" src="/img/print.png" value="Печать" onclick="print_page()"></input>
</div>
