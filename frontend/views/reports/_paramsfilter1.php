<?php  
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\DatePicker;
use frontend\models\Tickets;
use frontend\models\Report_Titotals;

/**
 * Filter panel for reports
 *
 * @var $model object should contain: district,datefrom,dateto,calltype
 *                may contain: status
 */
$this->registerCss( '@media print { 
   div#paramsfilter1 { display: none; } }'
);
$this->registerCss( '@media screen {  div#paramsfilter2 { display: none; } 
}');
?>
 <style> .left {float:left;}  
    .clearfix:after {
    content: "";
    display: table;
    clear: both;
    }

</style>

<?


  //--- Filtering panel1: District,Calltype,Datefrom,Dateto
  echo "<div id='paramsfilter1'>";
      echo '<p>';
      /* Trying to use ActiveForm...
      $form = ActiveForm::begin(['id' => 'filtern-form','action'=>Url::toRoute(['titotals']),'method'=>'get','options'=>['class'=>'form-inline']]);
        echo $form->field($model,'district')->dropDownList(Tickets::getDistrictsList(),['class'=>'form-control']).' ';
        echo $form->field($model,'calltype')->dropDownList(Tickets::getCallTypesList(),['class'=>'form-control']).' ';
        echo $form->field($model,'datefrom')->widget(DatePicker::className(),['dateFormat'=>'dd-MM-yyyy','options'=>['class'=>'form-control']]).' ';
        echo $form->field( $model, 'dateto' )->widget(DatePicker::className(),['dateFormat' => 'dd-MM-yyyy','options'=>['class'=>'form-control']]).' ';
      echo  '<div class="form-group">';
      echo Html::submitButton(Yii::t('app','Set'),['class'=>'form-control submit btn btn-success']);
      echo  '</div>';
       ActiveForm::end();
      */
       
      //----Filter all button
      echo Html::beginForm([/*'titotals'*/$this->context->getRoute()],'get',['class'=>'form','id'=>'formFltr1']);
      echo '<div class="row">';
      
      //----Districts list
      if( array_key_exists('district',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Район :'.
        Html::dropDownList('district', $model->district,  Tickets::getDistrictsList(),['class'=>'form-control']).'</div>';
      }
      //----Device type
      if( array_key_exists('f_tidevicetype',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Оборудование :'.
        Html::dropDownList('f_tidevicetype', $model->f_tidevicetype,  Tickets::getDeviceTypesList(),['class'=>'form-control']).'</div>';
      }
      //----Report year
      if( array_key_exists('repyear',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Год :'.
        Html::dropDownList('repyear', $model->repyear,  Tickets::getYearsList(false),['class'=>'form-control']).'</div>';
      }
      //----Report month
      if( array_key_exists('repmonth',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Месяц :'.
        Html::dropDownList('repmonth', intval($model->repmonth),  Tickets::getMonthsList(false),['class'=>'form-control']).'</div>';
      }
      //----Date from
      if( array_key_exists('datefrom',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Дата&nbspс :'.
        DatePicker::widget(['name'  => 'datefrom',
                                    'value'  => $model->datefrom,
                                    'dateFormat' => 'dd-MM-yyyy',
                                    'options'=>['class'=>'form-control']]).'</div>';;
      }
      //----Date up to
      if( array_key_exists('dateto',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Дата&nbspпо :'.
        DatePicker::widget(['name'  => 'dateto',
                                    'value'  => $model->dateto,//date('d-M-y'),
                                    'dateFormat' => 'dd-MM-yyyy',
                                    'options'=>['class'=>'form-control']]).'</div>';;
      }
      //----Call types list
      if( array_key_exists('calltype',$model->attributes ) ) {
        $ctlist=Tickets::getCallTypesList();unset($ctlist['']);$ctlist=[""=>"Все","1"=>'ЦДС',"2"=>'ОДС (без ЦДС)']+$ctlist;
        echo '<div class="form-group col-sm-2">'.' Источник :'.
        Html::dropDownList('calltype', $model->calltype,  $ctlist,['class'=>'form-control']).'</div>';;
      }
      //----Status
      if( array_key_exists('status',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Статус : '.
        //Html::dropDownList('status', $model->status,  [""=>'Все']+Yii::$app->params['TicketStatus'],['class'=>'form-control','style'=>'width:14%']).' ';
        Html::dropDownList('status', $model->status,  Report_Titotals::getStatusesList(),['class'=>'form-control'/*,'style'=>'width:14%'*/]).'</div>';
      }
      //----StatusRemote
      if( array_key_exists('statusremote',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2"> Статус 1562: '.
        Html::dropDownList('statusremote', $model->statusremote,  Report_Titotals::getStatusesListRemote(),['class'=>'form-control']).'</div>';
      }
      //---Additional query string
      if( array_key_exists('tifindstr',$model->attributes ) ) {
        //echo '<div style="margin-top:5px">Заявка:'.
        echo '<div class="form-group col-sm-2" id="divtifindstr"> Заявка:'.
        Html::textinput('tifindstr', $model->tifindstr,['class'=>'form-control']).'</div>';
      }
      //---Objectcode
      if( array_key_exists('tiobjectcode',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2" id="divtiobjectcode"> Инв.номер:'.
        Html::textinput('tiobjectcode', $model->tiobjectcode,['class'=>'form-control']).'</div>';
      }
      //---Executant division
      if( array_key_exists('f_tiexecutantdesk',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2" id="divtivexecutantdesk"> Подр.исполнителя:'.
        Html::dropDownList('f_tiexecutantdesk', $model->f_tiexecutantdesk,  Tickets::getMasterDesksList(TRUE),['class'=>'form-control']).'</div>';
      }
      //---Executant
      if( array_key_exists('f_tiexecutant',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2" id="divtivexecutant"> Исполнитель:'.
        Html::textinput('f_tiexecutant', $model->f_tiexecutant,['class'=>'form-control']).'</div>';
      }
      //---opstatus,Did,180904
      if( array_key_exists('opstatus',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2" id="opstatus"> Статус:'.
        Html::dropDownList('opstatus',$model->opstatus,[0=>'Все',1=>'Остановлен',2=>'Не определен', 3=>'В работе',/* 4=>'без останова'*/],['class'=>'form-control']).'</div>';
      }
      //---OOS status
      if( array_key_exists('f_statusoos',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2" id="divstatusoos"> Статус оборудования:'.
        Html::dropDownList('f_statusoos', $model->f_statusoos,  Report_Titotals::getStatusesOosList(),['class'=>'form-control']).'</div>';
      }
      //---OOS type
      if( array_key_exists('f_typeoos',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2" id="divstatusoos"> Неисправность:'.
        Html::dropDownList('f_typeoos', $model->f_typeoos,  Report_Titotals::getTypesOosList(),['class'=>'form-control']).'</div>';
      }
      //---Report page size
      if( array_key_exists('reportpagesize',$model->attributes ) ) {
        echo '<div class="form-group col-sm-2" id="reportpagesize"> Строк:'.
        Html::dropDownList('reportpagesize',$model->reportpagesize,[20=>'20',25=>'25',50=>'50',100=>'100',200=>'200',500=>'500',1000=>'1000',0=>'Все'],['class'=>'form-control']).'</div>';
      }
      echo '<div class="form-group col-sm-1"><br>';
      echo Html::submitButton(Yii::t('app','Choose'),['class'=>'submit btn btn-success','id'=>'submitFltr1']).'</div>';
      echo '</div>'; /* End of row*/
      echo Html::endForm();
      echo '</p>';
  echo '</div>';



  // ****** Print *****

  echo "<div id='paramsfilter2'>";
      echo '<p>'; 
        // district
        if( array_key_exists('district',$model->attributes ) ) {
          $district=$model->district; 
          if ($district=='') { $district='Все'; }
          echo '<div class="left"> Район: '.$district.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //----Device type
        if( array_key_exists('f_tidevicetype',$model->attributes ) ) {
          $f_tidevicetype=$model->f_tidevicetype; 
          if ($f_tidevicetype=='') { $f_tidevicetype='Все'; }
          echo '<div  class="left" > Оборудование: '.$f_tidevicetype.'&nbsp &nbsp &nbsp &nbsp </div>'; 
        }
        //----Report year
        if( array_key_exists('repyear',$model->attributes ) ) {
          $repyear=$model->repyear;
          if ($repyear=='') { $repyear='Все'; }
          echo '<div class="left" > Год: '.$repyear.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //----Report month
        if( array_key_exists('repmonth',$model->attributes ) ) {
          $repmonth=$model->repmonth;
          if ($repmonth=='') { $repmonth='Все'; }
          echo '<div class="left" > Месяц: '.$repmonth.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //----Date from
        if( array_key_exists('datefrom',$model->attributes ) ) { 
          $datefrom=$model->datefrom;
          if( array_key_exists('dateto',$model->attributes ) ) 
/*            { $dateto=$model->dateto;
              if ($datefrom==$dateto) { //одна дата
              echo '<div class="left" > Дата&nbsp:&nbsp'.$datefrom.'&nbsp &nbsp &nbsp &nbsp </div>'; 
              goto next; }
            }//разные даты*/
          echo '<div class="left" > Дата&nbsp:&nbspс&nbsp'.$datefrom.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //----Date up to
        if( array_key_exists('dateto',$model->attributes ) ) {
          $dateto=$model->dateto;
          echo ' по&nbsp'.$dateto.'&nbsp &nbsp &nbsp &nbsp </div>';
        } 
//next:
        //----Call types list
        if( array_key_exists('calltype',$model->attributes ) ) {
          $ctlist=Tickets::getCallTypesList();unset($ctlist['']);
          $ctlist=[""=>"Все","1"=>'ЦДС',"2"=>'ОДС (без ЦДС)']+$ctlist;
          $calltype=$model->calltype; 
          foreach($ctlist as $key => $vol) 
            { if ($calltype==$key) { $calltype=$vol; } } 
          echo '<div class="left" >'.' Источник: '.
          $calltype.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //----Status
        if( array_key_exists('status',$model->attributes ) ) {
          $statuslist=Report_Titotals::getStatusesList();
          $status1=$model->status;
          foreach($statuslist as $key => $vol) 
            {  if ($status1==$key) { $status=$vol; }  } 
          echo '<div class="left" > Статус: '.
          $status.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //----StatusRemote
        if( array_key_exists('statusremote',$model->attributes ) ) {
          $statuslistremote=Report_Titotals::getStatusesListRemote();
          $statusremote1=$model->statusremote;
          foreach($statuslistremote as $key => $vol) 
            {  if ($statusremote1==$key) { $statusremote=$vol; }  }
          echo '<div class="left" > Статус 1562: '.
          $statusremote.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //---Additional query string
        if( array_key_exists('tifindstr',$model->attributes ) ) {
          echo '<div class="left"  id="divtifindstr"> Заявка:'.
          $model->tifindstr.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //---Objectcode
        if( array_key_exists('tiobjectcode',$model->attributes ) ) {
          echo '<div class="left"  id="divtiobjectcode"> Инв.номер:'.
          $model->tiobjectcode.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //---Executant division
        if( array_key_exists('f_tiexecutantdesk',$model->attributes ) ) {
          $exdivisionlist=Tickets::getMasterDesksList(TRUE);
          $exdivision1=$model->f_tiexecutantdesk;
          foreach($exdivisionlist as $key => $vol) 
            {  if ($exdivision1==$key) { $exdivision=$vol; }  }
          echo '<div class="left"  id="divtivexecutantdesk"> Подр.исполнителя:'.
          $exdivision.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //---Executant
        if( array_key_exists('f_tiexecutant',$model->attributes ) ) {
          echo '<div class="left"  id="divtivexecutant"> Исполнитель:'.
          $model->f_tiexecutant.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //---OOS status
        if( array_key_exists('f_statusoos',$model->attributes ) ) {
          $oosstatuslist=Report_Titotals::getStatusesOosList();
          $oosstatus1=$model->f_statusoos;
          foreach($oosstatuslist as $key => $vol) 
            {  if ($oosstatus1==$key) { $oosstatus=$vol; }  }
          echo '<div class="left"  id="divstatusoos"> Статус оборудования: '.
          $oosstatus.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //---OOS type
        if( array_key_exists('f_typeoos',$model->attributes ) ) {
          $oostypelist=Report_Titotals::getTypesOosList();
          $oostype1=$model->f_typeoos;
          foreach($oostypelist as $key => $vol) 
            {  if ($oostype1==$key) { $oostype=$vol; }  }
          echo '<div class="left"  id="divstatusoos"> Неисправность: '.
          $oostype.'&nbsp &nbsp &nbsp &nbsp </div>';
        }
        //---Report page size
        if( array_key_exists('reportpagesize',$model->attributes ) ) {
          $reportpagesize=$model->reportpagesize;
          if ($reportpagesize==0) {$reportpagesize='Все';}
          echo '<div class="left" id="reportpagesize"> Строк:'.$reportpagesize
          .'&nbsp &nbsp &nbsp &nbsp </div>';
        }
      echo '<div class="clearfix">  </div> <br>';
      echo '</p>';
      echo "</div>";
      //echo Html::submitButton(Yii::t('app','Choose'),['class'=>'submit btn btn-success','id'=>'submitFltr1']).'</div>';
      //echo Html::endForm();
?>
