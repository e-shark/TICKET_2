<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Отчет по повторным заявкам';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//--- Format page for printing
$this->registerCss( '@media print { 
        h1,.wrap>.container{margin:0;padding:0;}
        .footer, .breadcrumb, .pagination, 
        button#submitFltr1, input#printButton,  div#divtivexecutant,div#divtifindstr, div#reportpagesize { display: none; } 
        .report-holder a[href]::after {content: "";}
        div.report-holder *:not(h1){font-size:12px}
}');

$this->registerJs( 'function print_page(){window.print() ;}', yii\web\View::POS_HEAD);
?>

<div class="report-holder">
	<h1><?= Html::encode($this->title) ?></h1>
	 
    <?php echo $this->render('_paramsfilter1.php', [ 'model'=>$model]); ?>

    <?=  GridView::widget([
    		'dataProvider' => $provider,
    		'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
            	[
                'label' =>"Количество заявок",
                'attribute' => 'cnt',
            	],
            	[
                'label' =>"Инв.Номер",
                'attribute' => 'tiobjectcode',
                'content' => function($data) use ($model) { 
                    $url = Url::toRoute(['reports/ticketslist', 'tifindstr' => $data['tiobjectcode'],'district' => $model->district,'calltype' => $model->calltype,'datefrom' => $model->datefrom,'dateto' => $model->dateto]);
                    return "<a href=$url>".$data['tiobjectcode'].'</a>';}
            	],
            	[
                'label' =>"Адрес",
                'attribute' => 'tiaddress',
                'format'=>'html',
                'content'=>function($data){return 
                    ((1==$data['tiobject_id']) ? "<span style='color:#228B22 ' class='glyphicon glyphicon-resize-vertical'>":
                    ((2==$data['tiobject_id']) ? "<span style='color:#FF4500 'class='glyphicon glyphicon-flash'>":
                    ((3==$data['tiobject_id']) ? "<span style='color:#4169E1 'class='glyphicon glyphicon-phone'>":"")))
                    .mb_substr($data['tiregion'],0,3).'.'
                    ."</span>&nbsp".$data['tiaddress'];},
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
                ],
            ]
		]);
	?>
    <input id="printButton" type="image" src="/img/print.png" value="Печать" onclick="print_page()"></input>
</div>