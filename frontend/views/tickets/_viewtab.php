<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use frontend\models\Tickets;
/**
 *  Ticket view partial view
 */
//print_r($model->ticket);
?>
<div class="tickets-_viewtab">
    <?php 
        Yii::$app->formatter->defaultTimeZone ="Etc/GMT-2";
        //$isTicketRead = Tickets::isTicketBeenRead($model->ticket['id'],$model->ticket['tiexecutant_id']);
        if(strpos($model->ticket['tistatus'],'COMPLETE') )$isTicketRead = TRUE;
        $tiAttributes = [
            [                   
            'label' => 'Дата открытия заявки ',
            //'format'=>['date','dd-MM-yyyy  HH:m:s'],
            //'value' => $model->ticket['tiopenedtime'],
            'value' => date("d-m-Y H:i:s",strtotime($model->ticket['tiopenedtime'])),
            'contentOptions'=>['style'=>'font-weight:bold']
            ],
            [                   
            'label' => 'Плановый срок исполнителю ',
            //'format'=>['date','dd-MM-yyyy  HH:m'],
            //'value' => $model->ticket['tiiplannedtime'],
            'value' => $model->ticket['tiiplannedtime']?date("d-m-Y H:i",strtotime($model->ticket['tiiplannedtime'])):'Не установлен',
            'contentOptions'=> (( strtotime($model->ticket['tiiplannedtime']) < time() ) &&
                                ( !in_array( $model->ticket['tistatus'], ['MASTER_COMPLETE','DISPATCHER_COMPLETE','1562_COMPLETE'] ) ) ) ?
                                //('MASTER_COMPLETE'!= $model->ticket['tistatus'])) ? 
                                ['style'=>' color:red;font-weight:bold'] : ['style'=>'color:#2d862d;font-weight:bold']
            ],
            [                   
            'label' => 'Адрес',
            'value' => ($model->isUserDispatcher()?
                        ('<span style="font-weight:bold;color:#E9967A">'.$model->ticket['tiregion'].' р-н</span><br>'):'').
                        $model->ticket['tiaddress'],
            'format'=>'html'
            ],
            [                   
            'label' => 'Причина обращения',
            'value' => $model->ticket['tiproblemtypetext'].' ('.$model->ticket['tiproblemtext'].')<br>'.$model->ticket['tidescription'],
            'format'=>'html'
            ],
            [                   
            'label' => 'Статус',
            'format'=>'html',
            'value' => 
                    '<img src='.($model->ticket['tiopstatus']=='0'?"/img/red_light-24.ico":($model->ticket['tiopstatus']=='1'?"/img/green_light-24.ico":"/img/yellow_light-24.ico")).'>'. // In-Operation status,180605,vpr
                    '<strong> '.date("d-m-Y H:i:s",strtotime($model->ticket['tistatustime'])).' : '.
                      Yii::$app->params['TicketStatus'][$model->ticket['tistatus']].'</strong>'.
                      ($model->tilogarray[0]['tiltext']?"<br><i>".$model->tilogarray[0]['tiltext'].'</i>':"").  // Last comment from Log, vpr, 17.04.2018
                      (empty($model->ticket['tistatusremote']) ? '':('<br><span style="font-weight:normal;color:#E9967A">1562: '.$model->ticket['tistatusremote'])).'</span>',
            //'contentOptions'=>  ('MASTER_COMPLETE'== $model->ticket['tistatus'] ) ? ['style'=>'background-color:lightgreen']:[],
            'contentOptions'=>  (strpos($model->ticket['tistatus'],'COMPLETE') ) ? ['style'=>'background-color:lightgreen']:
                                (strpos($model->ticket['tistatus'],'REFUSE')  ? ['style'=>'background-color:yellow']:
                                (strpos($model->ticket['tistatus'],'REASSIGN')?['style'=>'background-color:red;color:white']:[])),
            ],
            [                   
            'label' => 'Приоритет',
            //'value' => ($model->ticket['tipriority']=='NORMAL')?'Обычный':'Высокий',
            'value' =>Yii::$app->params['TicketPriority'][$model->ticket['tipriority']],
            'contentOptions'=> ( $model->ticket['tipriority'] < 'NORMAL') ? ['style'=>'color:red']:[]
            ],
            ];
            if( !$model->isUserFitter() ) $tiAttributes = array_merge( $tiAttributes, [
                [                   
                'label' => 'Открыл заявку',
                'format'=>'html',
                'value' => $model->ticket['tioriginator'].'<br> '.$model->ticket['originatordeskname'],
                ],
                [                   
                'label' => 'Источник',
                'value' => $model->ticket['ticalltype']
                ],
                [                   
                'label' => 'Заявитель',
                'format'=>'html',
                'value' => ($model->ticket['ticaller']?$model->ticket['ticaller']:'-').
                            ' (тел.'.($model->ticket['ticallerphone']?$model->ticket['ticallerphone']:'-').')<br>'.$model->ticket['ticalleraddress']
                ],
                [                   
                'label' => 'Плановый срок',
                //'format'=>['date','dd-MM-yyyy  HH:m'],
                //'value' => $model->isUserFitter() ? $model->ticket['tiiplannedtime']:$model->ticket['tiplannedtimenew'],
                'value' => date("d-m-Y H:i",strtotime($model->isUserFitter() ? $model->ticket['tiiplannedtime']:$model->ticket['tiplannedtimenew'])),
                'contentOptions'=>( ( strtotime($model->ticket['tiplannedtimenew']) < time() ) &&
                                ( !in_array( $model->ticket['tistatus'], ['MASTER_COMPLETE','DISPATCHER_COMPLETE','1562_COMPLETE'] ) ) ) ?
                                ['style'=>'color:red'] : []
                ],
                [                   
                'label' => 'Плановый срок поставки МТЦ',
                'value' => $model->ticket['tisplannedtime'],
                'contentOptions'=> (( strtotime($model->ticket['tisplannedtime']) < time() ) &&
                                    ( !in_array( $model->ticket['tistatus'], ['MASTER_COMPLETE','DISPATCHER_COMPLETE','1562_COMPLETE'] ) ) ) ?
                                    ['style'=>'color:red'] : []
                ],
                [                   
                'label' => 'Объект',
                'format'=>'html',
                'value' => $model->ticket['tiobject'].' № <span style="font-weight:bold"> '.$model->ticket['tiobjectcode'].'</span> 
                (Дом № <span style="font-weight:bold">'.$model->ticket['tifacilitycode'].'</span> ) <br> '.$model->ticket['divisionname'],
                //'contentOptions'=> ['style'=>' font-weight:bold']
                ],
                [                   
                'label' => 'Принадлежность оборудования',
                'format'=>'html',
                'value' => 
                'Собственник : <span style="font-weight:bold;color:#E9967A">'.$model->ticket['eqocompany'].'</span><br>'.
                'Исполнитель : <span style="font-weight:bold;color:#E9967A">'.$model->ticket['eqscompany'].'</span><br>'.
                'Субподрядчик: <span style="font-weight:bold;color:#E9967A">'.$model->ticket['eqsubscompany'].'</span><br>',
                //'contentOptions'=> ['style'=>' font-weight:bold']
                ],
                [                   
                'label' => 'Ответственное подразделение',
                'value' => $model->ticket['deskname']
                ],
                [ 
                'label' => 'Исполнитель',
                'format'=>'html',
                'value' => isset($model->ticket['executant']) ?
                    ($model->ticket['executantdeskname'].'<br>'.$model->ticket['executant'].
                        ($model->ticket['tiexecutantread'] ? ' <span class="glyphicon glyphicon-folder-open" style="color:green"></span> ':
                    ' <span class="glyphicon glyphicon-envelope" style="color:red"></span> ')):'-',
                //'value' => $model->ticket['executant'].($isTicketRead?'':' (не прочитано)'),
                'contentOptions'=> $model->ticket['tiexecutantread'] ? []:['style'=>' font-weight:bold']
                ],
                [                   
                'label' => 'Неисправность',
                'format'=>'html',
                'value'=>($model->ticket['oostypetext']?'<b>'.$model->ticket['oostypetext'].'</b>':'<b style="color:red">ПРИЧИНА НЕ ОПРЕДЕЛЕНА</b>').'<br>'.
                          $model->ticket['tiresulterrorcode'].": ".$model->ticket['tiresulterrortext']
                ],
            ]);
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $tiAttributes,
    ]) ?>
    <?php /*print_r ($model->flist)*/ ?>

    
    
    <?php /*All parameters passing to beginForm will be in get, to be in the post, hidden fields need to be defined */?>
    <?= Html::beginForm(['appoint','ticketId'=>$model->ticket['id']],'post') ?>

    <?= Html::hiddenInput('ticketId'    ,$model->ticket['id'])?>
    <?= Html::hiddenInput('senderId'    ,$model->useroprights['id'])?>
    <?= Html::hiddenInput('senderdeskId',$model->useroprights['division_id'])?>
    <?= Html::hiddenInput('servicedeskId',$model->ticket['tidivision_id'])?>
    <?= Html::hiddenInput('actor'       ,$model->actor )?>


    <?php // Comment
        if(($model->isUserMaster()    && (!in_array($model->ticket['tistatus'],['MASTER_COMPLETE','DISPATCHER_COMPLETE'] )))   ||
             ($model->isUserDispatcher() && (!in_array($model->ticket['tistatus'],['OPERATOR_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE'])))   ||
             ($model->isUserFitter()    && (!in_array($model->ticket['tistatus'],['EXECUTANT_COMPLETE','MASTER_COMPLETE','DISPATCHER_COMPLETE'] )))){ ?>
            
    <h4 align='middle' class="glyphicon     glyphicon glyphicon-pencil" style='color:RoyalBlue'></h4>
    <?= Html::label('Комментарий :') ?>
    <?= Html::input('text', 'tiltext','',['class'=>'form-control','size'=>50])?>
    <?php }?>
    
    <?php 
    
    //---PANEL: OOS TYPE panel
    if( ( $model->isUserMaster()      && (!in_array($model->ticket['tistatus'],[/*'MASTER_COMPLETE',*/'DISPATCHER_COMPLETE'] ) ) ) || 
        ( $model->isUserDispatcher() && (!in_array($model->ticket['tistatus'],['OPERATOR_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE'])) ) ) {

        $tihours = intval((time()-strtotime($model->ticket['tiopenedtime']))/3600);//echo $tihours;
        $oostypepanelclass  = $model->ticket['tioostype_id'] ? "panel panel-info":"panel panel-danger";
        echo "<div class='$oostypepanelclass' ><div class='panel-heading'>".
            Html::label('Причина неисправности оборудования:').' ';
            if(!$model->ticket['tioostype_id'])echo Html::label('На указание причины осталось часов: '.($tihours<24?24-$tihours:0));
            echo '<div class="row">'.
                '<div class="col-md-3">'.
                    Html::dropDownList('tioostypeId', $model->ticket['tioostype_id'],  $model->getOosTypesList($model->ticket['devicetypecode']),['class'=>'form-control']).
                '</div>';
                 if(!$model->ticket['tioostype_id'])echo '<div class="col-md-6">'.
                    "<b>ВНИМАНИЕ!</b> Необходимо указать причину неисправности в течение не более 24&nbspчасов c момента открытия заявки! Истекло часов : <b>$tihours</b>".
                '</div>';
                 echo '<div class="col-md-2">'.
                    Html::submitButton(Yii::t('app','Save'),['class'=>'submit btn btn-primary','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_ASSIGN_OOS']) ]).
                '</div>'.
        '</div></div></div>';
    }
    //---PANEL: OOS TIMES panel
    if( ( $model->isUserMaster()      && (!in_array($model->ticket['tistatus'],[/*'MASTER_COMPLETE',*/'DISPATCHER_COMPLETE'] ) ) ) || 
        ( $model->isUserDispatcher() && (!in_array($model->ticket['tistatus'],['OPERATOR_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE'])) ) ||
        ($model->isUserFitter() && (FALSE === mb_strpos($model->ticket['tistatus'],'COMPLETE') ) ) ){

        $oospnlclass  = $model->hasOos ? "panel panel-danger" : "panel panel-success";
        $oosdatespanelstyle = $model->hasOos ? "" : "display:none;";

        echo "<div class='$oospnlclass' ><div class='panel-heading'>";
        echo Html::label('Информация об Аварийной Остановке Оборудования : ')." ".
             Html::label($model->hasOos ?($model->oosHours." час. простоя. ".($model->hasOosNow?"ОТКЛЮЧЕН":"РАБОТАЕТ")):($model->ticket['tiopstatus']=='1'?"РАБОТАЕТ":"-"),null,['style'=>'font-size:20px;','align'=>'center']);
        echo '<div class="row">';
            //--- #oosdatespanel -  panel's visibility is controlled by PHP/JS regarding on the ticket's OOS state
            echo "<div id='oosdatespanel' style='$oosdatespanelstyle'>";
                //---Choose the OOS begin time
                echo '<div class="col-md-2">';
                    echo 'Дата остановки';
                    echo DatePicker::widget(['name'  => 'tioosbegin',
                                        'value'  => $model->ticket['tioosbegin'],
                                        'dateFormat' => 'dd-MM-yyyy',
                                        'options'=>['class'=>'form-control']]);
                echo '</div>';
                echo '<div class="col-md-1"><br>';
                    echo Html::dropDownList('tioosbegintm',  Yii::$app->formatter->asDate($model->ticket['tioosbegin'],"HH"), $model->getHoursList(),['class'=>'form-control-sm','style'=>'width:100%;']);
                echo '</div>';
                //---Choose the OOS end time
                echo '<div class="col-md-2">';
                    echo 'Дата запуска';
                    echo DatePicker::widget(['name'  => 'tioosend',
                                        'value'  => date('d-m-Y',$model->hasOosNow?time():strtotime($model->ticket['tioosend'])), //09.08.2018,vpr
                                        'dateFormat' => 'dd-MM-yyyy',
                                        'options'=>['class'=>'form-control']]);
                echo '</div>';
                echo '<div class="col-md-1"><br>';
                    echo Html::dropDownList('tioosendtm',  date('H',$model->hasOosNow?time():strtotime($model->ticket['tioosend'])), $model->getHoursList(),['class'=>'form-control-sm']);
                echo '</div>';
                echo '<div class="col-md-2"><br>'.
                    Html::submitButton(Yii::t('app',$model->hasOosNow ?'Run':'Save'),['class'=>'submit btn btn-danger','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_EDIT_OOS']) ]).' '.
                '</div>';
            echo '</div>';  // end of #oosdatespanel
            //---OOS IN/OUT button
            $oosbtntext=$model->hasOos ?"Отменить останов":("Остановить");
            $oosbtnclass=$model->hasOos ?"submit btn btn-primary":"submit btn btn-danger";
            echo '<div class="col-md-2"><br>'.
                Html::submitButton(Yii::t('app',$oosbtntext),['class'=>"$oosbtnclass",'formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_SWITCH_OOS']) ]).
            '</div>';
        echo '</div></div></div>';
    } 

        //---PANEL: Set ticket planned time only if user is 
        if( ( $model->isUserMaster()      && (!in_array($model->ticket['tistatus'],[/*'MASTER_COMPLETE',*/'DISPATCHER_COMPLETE'] ) ) ) || 
            ( $model->isUserDispatcher() && (!in_array($model->ticket['tistatus'],['OPERATOR_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE'])) ) ) {
            echo
            '<div class="panel panel-info"><div class="panel-heading">';
                echo Html::label('Плановый срок по заявке:');
                echo '<div class="row"><div class="col-md-6">';
                echo DatePicker::widget(['name'  => 'ticketplanneddate','value'  => $model->ticket['tiplannedtimenew'],'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
                echo '</div><div class="col-md-3">';
                echo Html::submitButton(Yii::t('app','Set'), ['class'=>'submit btn btn-primary','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_ASSIGN_DATE']) ]);
            echo
            '</div></div></div></div>';
        }

        //---EXECUTANT PANEL
        if(($model->isUserMaster()&&(!in_array($model->ticket['tistatus'],['MASTER_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE','MASTER_ASSIGN','MASTER_REASSIGN']))) || 
           ($model->isUserDispatcher() && 
            ((!in_array($model->ticket['tistatus'],['DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE'])) && 
            ((!$model->ticket['tidesk_id'])||(($model->useroprights['division_id']==$model->ticket['tidesk_id'])&&(!$model->ticket['tiexecutant_id']))) ||
            (('EXECUTANT_COMPLETE' == $model->ticket['tistatus'] )&&($model->useroprights['division_id']==$model->ticket['tidesk_id']))
            ) ) ) {
            echo '<div class="panel panel-success"><div class="panel-heading">';
            echo Html::label('Исполнитель'.($model->isUserDispatcher()?' ЛАС':'').', плановый срок исполнителю :');
            echo '<div class="row">';

                //---Choose the executant
                echo '<div class="col-md-4">';
                    echo Html::dropDownList('receiverId', /*$model->ticket['tiexecutant_id']*/$model->respfitterId,  $model->fitterslist,['id'=>'selectExecutant','class'=>'form-control','onChange'=>'onSelectExecutant()']);
                echo '</div>';

                //---Set planned time for executant
                echo '<div class="col-md-2">'.
                    DatePicker::widget(['name'  => 'fitterplanneddate',
                                        'value'  => $model->ticket['tiiplannedtime'] ? $model->ticket['tiiplannedtime']:$model->ticket['tiplannedtimenew'],
                                        'dateFormat' => 'yyyy-MM-dd',
                                        'options'=>['class'=>'form-control']]).
                '</div>';

                //---Assign/Reassign buttons
                echo '<div class="col-md-4">';
                    if( 'EXECUTANT_COMPLETE' == $model->ticket['tistatus'] ) { 
                       if((!$model->hasOosNow) AND !empty($model->ticket['tioostype_id'])) 
                            /*echo Html::submitButton(Yii::t('app','Accept job'),['class'=>'submit btn btn-success','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_COMPLETE']) ]).' ';*/
                        echo 
                        Html::submitButton(Yii::t('app','Reject job'),['class'=>'submit btn btn-danger','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_REASSIGN']) ]);
                    }  else {
                        echo
                        Html::submitButton(Yii::t('app','Appoint'), ['id'=>'buttonSelectExecutant','class'=>'submit btn btn-primary','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_ASSIGN']) ]+($model->respfitterId?[]:['style'=>'visibility:hidden']));
                    }
                echo '</div>';
            echo
            '</div></div></div>';
        }

        //---TRANSFER to MASTER PANEL - show when responsible division yet not set or set to dispatcher but executant has not been assigned
        if( $model->isUserDispatcher() && (!in_array($model->ticket['tistatus'],['OPERATOR_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE']))) 
            if((!$model->ticket['tidesk_id']) OR (($model->useroprights['division_id']==$model->ticket['tidesk_id'])AND (!$model->ticket['tiexecutant_id']))) {
            echo '<div class="panel panel-info"><div class="panel-heading">';
            echo Html::label('Назначить Ответственное подразделение:');
            echo '<div class="row"><div class="col-md-6">';
            echo Html::dropDownList('deskId', $model->ticket['tidivision_id'],  $model->getMasterDesksList(FALSE,$model->ticket['devicetypecode']),['class'=>'form-control','id'=>'selectMaster','onChange'=>'onSelectMaster()']);
            echo '</div><div class="col-md-3">';
            echo ' '.Html::submitButton(Yii::t('app','Transfer to Master'),['id'=>'buttonSelectMaster','class'=>'submit btn btn-primary','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_ASSIGN_MASTER']) ]+($model->ticket['tidivision_id']?[]:['style'=>'visibility:hidden'])).'<br><br>';
            echo '</div></div></div></div>';
        }


        //------------Accept buttons
        if( ( $model->isUserMaster()     && (!in_array($model->ticket['tistatus'],['MASTER_ACCEPT'] ) ) )  ||
            ( $model->isUserDispatcher() && (((($model->useroprights['division_id']!=$model->ticket['tidesk_id'])&&$model->ticket['tidesk_id'])OR($model->ticket['tiexecutant_id'])))OR(in_array($model->ticket['tistatus'],['DISPATCHER_COMPLETE','OPERATOR_COMPLETE'] )))) 
            echo Html::submitButton(Yii::t('app','Accept'),['class'=>'submit btn btn-success','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_ACCEPT']) ]);

        //------------Refuse buttons
        if( $model->isUserMaster() && (!in_array($model->ticket['tistatus'],['MASTER_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE']))) echo 
         ' '.Html::submitButton(Yii::t('app','Refuse'),['class'=>'submit btn btn-danger','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_REFUSE']) ]);

        //------------Close button
        if((!$model->hasOosNow) AND !empty($model->ticket['tioostype_id'])) {
            if( ( $model->isUserMaster()      && (!in_array($model->ticket['tistatus'],['MASTER_COMPLETE','DISPATCHER_COMPLETE'] ) ) ) || 
                ( $model->isUserDispatcher()) && (!in_array($model->ticket['tistatus'],['OPERATOR_COMPLETE','DISPATCHER_COMPLETE','KAO_COMPLETE','ITERA_COMPLETE'])) ) echo ' '.
                Html::submitButton(Yii::t('app','Close Ticket'),['class'=>'submit btn btn-success','formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_COMPLETE']) ]);
        } else if(!$model->isUserFitter()){ //---Alarming panels
            echo '<div class="panel panel-danger"><div class="panel-heading">';
            if($model->hasOosNow)echo '<div><b>ВНИМАНИЕ!</b> Оборудование остановлено! Для закрытия заявки введите дату и время запуска в панели Инфомации об Аварийной Остановке оборудования!</div>';
            if(empty($model->ticket['tioostype_id'])) echo '<div><b>ВНИМАНИЕ!</b> Причина неисправности не определена! Для закрытия заявки укажите причину неисправности в панели выбора Причины Неисправности оборудования!</div>';
            echo '</div></div>';
        }

        //------------Redirect to 1562 button
        if( $model->isUserDispatcher() ) if( FALSE !== mb_strpos( $model->ticket['ticalltype'], '1562' ) ) if( !empty($model->ticket['ticoderemote'] ) ) {
            $url2062="http://062.mvk/LIFT/card_pere.php?".http_build_query(['c'=>$model->ticket['ticoderemote'],'m'=>6 ]);
            //$url2062="https://062.city.kharkov.ua/LIFT/card_pere.php?".http_build_query(['c'=>$model->ticket['ticoderemote'],'m'=>6 ]);
            echo " <a href=\"$url2062\" target='_blank' hreflang='en' charset='windows-1251' class='submit btn btn-default'>Перейти в систему 1562</a>";
        }

        //----User is FITTER:
        if( $model->isUserFitter() ) if( FALSE === mb_strpos($model->ticket['tistatus'],'COMPLETE') ){ 
            
            if(!empty($errorCodeGroupsList=$model->getDeviceErrorCodeGroupsList4User())) {
                $url4ErrorCodesListHtml = Url::toRoute(["get-error-codes-list-html"]);
                echo Html::dropDownList('errorcodegroup',0,$errorCodeGroupsList,['id'=>'errorCodeGroupsList','class'=>'form-control','onChange'=>'onSelectErrorCodeGroup()']);
            }

            echo "<div id='errorCodesListHolder'>".Html::dropDownList('errorcode',0,$model->getDeviceErrorCodesList4User(),['class'=>'form-control']).'</div>';

            echo Html::submitButton(Yii::t('app','Done'), ['class' => 'submit btn btn-primary','formaction'=>Url::toRoute(['appoint','tistatus'=>'EXECUTANT_COMPLETE']) ]).' ';
            echo Html::submitButton(Yii::t('app','Refuse'),['class'=>'submit btn btn-danger','formaction'=>Url::toRoute(['appoint','tistatus'=>'EXECUTANT_REFUSE']) ]);
        }
    ?>
    <?= Html::endForm() ?>
    
  
</div>

<?php
//--- Prepare the MODAL dialog window if user is the fitter of elevators service
if( !isset(Yii::$app->request->get()['blk_md_FitterStartStop']) ) if( $model->isUserFitter('L') )
    echo $this->context->renderpartial('md_FitterStartStop.php', ['model' => $model]);

$script1 = <<< JSVTAB
    $(window).load(function () {
        onSelectMaster();
        $('#md_FitterStartStop').modal();
        //onSelectExecutant();
    });
    function onSelectMaster(){
        if ($("#selectMaster").val() == 0)  $("#buttonSelectMaster").hide();
        else                                $("#buttonSelectMaster").attr("style","visibility:visible");
    }
    function onSelectExecutant(){
        $("#buttonSelectExecutant").attr("style","visibility:'visible'");
        if ($("#selectExecutant").val() == 0)   $("#buttonSelectExecutant").hide();
        else $("#buttonSelectExecutant").attr("style","visibility:visible");
    }
    function closeMd_FitterStartStop(){
        $('#md_FitterStartStop').modal('hide');
    }
    

    // Control the ErrorCodes drop downs for fitter, 14.08.2018,vpr
    function onSelectErrorCodeGroup(){
    $.ajax({
         url: '$url4ErrorCodesListHtml',
         type: "POST",
         dataType: "html",
         data: {ErrorCodeGroup: $("#errorCodeGroupsList").val()},
         success: function(datamas) {
                $("#errorCodesListHolder").html(datamas);
         },
         error:   function() {
            $("#errorCodesListHolder").html('Ajax Error');
         }

  });
  return false;
}

JSVTAB;
$this->registerJs($script1, yii\web\View::POS_END);

?>