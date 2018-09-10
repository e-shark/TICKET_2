<?php

use yii\helpers\Html;
//use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\helpers\Url;


//---assemble string for ticket device type
$tidevicestring=    ((1==$model->ticket['tiobject_id']) ? "<span style='color:#228B22;' class='glyphicon glyphicon-resize-vertical'>":
                    ((2==$model->ticket['tiobject_id']) ? "<span style='color:#FF4500 ' class='glyphicon glyphicon-flash'>":
                    ((3==$model->ticket['tiobject_id']) ? "<span style='color:#4169E1 ' class='glyphicon glyphicon-phone'>":"")))
                    /*.$model->ticket['tiobject']*/.'</span>';

//---assemble string with 1562 number 
$tiremotesystem=(FALSE!==strpos($model->ticket['ticalltype'],"1562"))?'1562':
                (FALSE!==strpos($model->ticket['ticalltype'],"Itera2")?'iДЖХ':
                (FALSE!==strpos($model->ticket['ticalltype'],"Itera3")?'iЖКС':'?'));                    
$ti1562nostr = $model->ticket['ticoderemote'] ?
        " <span style='font-size:60%;color:#E9967A'>(<span style='font-size:60%;color:#E9967A'> №$tiremotesystem</span> ".$model->ticket['ticoderemote'].')</span>':'';

$this->title = /*Yii::t('app','Ticket')*/'Заявка'.' № '.$model->ticket['ticode'];

//---Find the referrer & set the path to view
$refcontroller=Yii::$app->request->getReferrer(); //echo $refcontroller;
 if( FALSE !== strstr($refcontroller,'reports')){
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app','Reports'), 'url' => ['reports/index']];
    if( FALSE !== strstr($refcontroller,'ticketslist'))
        $this->params['breadcrumbs'][] = ['label' => 'Отчет: Список Заявок', 'url' => $refcontroller];
    if( FALSE !== strstr($refcontroller,'oosnow'))
        $this->params['breadcrumbs'][] = ['label' => 'Отчет по неработающим лифтам', 'url' => $refcontroller];
 }
else if( FALSE!==strstr($refcontroller,'tickets')){
    $this->params['breadcrumbs'][] = ['label' => /*Yii::t('app','Tickets')*/'Заявки', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="tickets-view">

    <h1><?= $tidevicestring." ".Html::encode($this->title).$ti1562nostr." ".Html::label(($model->oosHours>24)? 'Часов простоя : '.$model->oosHours:"",null,['class'=>'label label-danger'])?></h1>

    <?php    //---Opened Tickets panel
        //print_r($model->openedTickets);
        if(count($model->openedTickets)>1) {
            $url1="'".Url::toRoute(['tickets/view', 'id' => $model->openedTickets[0]['id']])."'";
            if( !$model->isUserFitter() )echo 
            "<div class='panel panel-danger' ><div class='panel-heading'><strong>".
                "ВНИМАНИЕ! По адресу открыта Заявка № <a href=$url1>".$model->openedTickets[0]['ticode']."</a>".
                " (всего открыто:".(count($model->openedTickets)).")".
                ". Причина обращения: ".($model->openedTickets[0]['tiproblemtypetext']?$model->openedTickets[0]['tiproblemtypetext']:'-').
            "</strong></div></div>";
        }
        //------ Assemble grid columns
        $ohcolumns=[
            [
                'label' => $this->title,
                'content' => $this->render('_viewtab', ['model' => $model]),
                'active' => true
            ],
            [
                'label' => Yii::t('app','Ticket history'),
                'content' => $this->context->renderpartial('_historytab', ['model' => $model]),
            ]
        ];
            if($model->objectTicketsProvider->totalCount>1)$ohcolumns[] = [
                'label' => Yii::t('app','Tickets for facility').' ('.$model->objectTicketsProvider->totalCount.')',
                'content' => $this->context->renderpartial('_ohistorytab', ['model' => $model]),
            ];
            $ohcolumns[]=[
                'label' => Yii::t('app','Ticket spair parts'),
                'content' => $this->context->renderpartial('_sparttab', ['model' => $model]),
            ];
            $ohcolumns[]=[
                'label' => Yii::t('app','Photo'),
                //'content' => $this->context->renderpartial('_uploadtab', ['model' => $model,'imagemodel' => $imagemodel]),
                'content' => $this->context->renderpartial('_docstab', ['model' => $model,'imagemodel' => $imagemodel]),
            ];
     ?>
    <?= Tabs::widget(['items' => $ohcolumns]);?>
</div>
