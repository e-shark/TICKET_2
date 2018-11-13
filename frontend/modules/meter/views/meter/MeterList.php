<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = Yii::t('meter','Meter list');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-holder">

	<h1><?= Html::encode($this->title) ?></h1>
    <div>
        <?php echo $this->render('_metersparamsfilter.php', [ 'model'=>$model]); ?>
    </div>
   	<?php   
        $mtrColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => Yii::t('meter','Serial №'),
                'content' => function($data){
                	return "<a href=".Url::toRoute(['meter/meter-info']).'&MeterId='.$data['id'].' target="_blank">'.$data['meterserialno'].'</a>';
                }
            ],
            [
                'label' => Yii::t('meter','Type'),
                'attribute' => 'metermodel',
            ],
            [
                'label' => Yii::t('meter','Address'),
                'attribute' => 'addrstr',
            ],

            [
                'label' => Yii::t('meter','Date'),
                'attribute' => 'mdatatime',
            ],
            [
                'label' => Yii::t('meter','Readings').", (".Yii::t('meter','kWh').")",
                //'attribute' => 'mdata',
                'content' => function($data){
                    return "<a href=".Url::toRoute(['meter/enter-reading']).'&MeterId='.$data['id'].(is_null($data['mdata'])?' class="not-set"':'').' >'.(is_null($data['mdata'])?"(не задано)":sprintf("%.1f",$data['mdata']/1000.0)).'</a>'; // отображаем показания в киловатах (а в базе хранится в ваттах)
                }
            ],
        ];

        echo GridView::widget([
    		'dataProvider' => $provider,
    		'columns' => $mtrColumns, 
		]);
	?>

    <?php echo Html::a(Yii::t('meter','Add meter'), Url::toRoute(['meter/meter-edit']), ['class' =>'submit btn btn-success']); ?>
</div>	

