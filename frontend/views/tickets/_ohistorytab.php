<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
//use yii\helpers\ArrayHelper;

/**
 *	Ticket history Partial view
 */

?>

<div class="tickets-_ohystorytab">
	 <?php 
	 	//Pjax::begin(['id'=>'tiohistoryGrid']);
	 	echo GridView::widget([
			'dataProvider' => $model->objectTicketsProvider,
			'columns' => [
				[	
					'label'=>'Номер',
					'attribute' => 'ticode', 
					'content' => function($data){ 
						$cremote = $data['ticoderemote'];
						$url = Url::toRoute(['tickets/view', 'id' => $data['id']]);
						return  "<a href=$url>".$data['ticode'].'</a>'.($cremote?" <span class='glyphicon glyphicon-link' style='color:#E9967A;vertical-align:super;font-size:80%'></span><br><span style='font-weight:normal;font-size:11px;color:#E9967A'>$cremote</span>":'') ;},
					'format'		=>'html'
			    ],
				[
					'label'=>'Дата создания',
					'attribute' => 'tiopenedtime', 		
					//'format'=>['date','dd-MM-yyyy  HH:m:s'],
					'content'=>function($data){return date("d-m-Y H:i:s",strtotime($data['tiopenedtime']));},
				],
				[
					'label'=>'Статус',
					'format'=>'html',
					'attribute' => 'tistatus',		
					'content' => function($data){ 
						if(!empty($data['tioosbegin'])){	 // Is the elevator now or had been before in the state of OOS ?
							$inOos=empty($data['tioosend']); // Is the elevator in OOS now?
							$ooshours = intval( ((empty($data['tioosend'])?time():strtotime($data['tioosend'])) - strtotime($data['tioosbegin']))/3600 );
							if((!$inOos AND ($ooshours<24)))unset($ooshours); // Do not show OOS infos if OOS-time < 24h
						}
						return Yii::$app->params['TicketStatus'][ $data['tistatus' ]].	// Status
						(empty($data['tistatusremote']) ? '' :							// Remote Status (1562) 
							('<br><span style="font-weight:normal;font-size:11px;color:#E9967A">1562: '.$data['tistatusremote'].'</span>')).
						((!$ooshours) ? '' :												// OOS infos
							('<br><span style="font-weight:bold;color:#9F0000">Часов в простое: '.$ooshours.'. '.($inOos?'ОТКЛЮЧЕН':'РАБОТАЕТ').'</span>'));
					},
					'contentOptions'=> function($data){ return 
						(strpos($data['tistatus'],'COMPLETE') ) ? ['style'=>'background-color:lightgreen']:
						(strpos($data['tistatus'],'REFUSE')     ? ['style'=>'background-color:yellow']:
						(strpos($data['tistatus'],'REASSIGN') ? ['style'=>'background-color:red; color:white']:[]));}
				],
				[
					'attribute' => 'tiproblemtypetext', 		
					'label'=>'Неисправность',
					'format'		=>'html',
					'content' => function($data){ return 
						($data['oostypetext']		?"<strong style='color:#585858'>".$data['oostypetext']."</strong>":
							"<strong style='color:red'>ПРИЧИНА НЕ ОПРЕДЕЛЕНА</strong>")."<br>".
						($data['tiproblemtypetext']	?$data['tiproblemtypetext']	."<br>":"").
						($data['tiproblemtext']		?$data['tiproblemtext']		."<br>":"").
						($data['tidescription']		?$data['tidescription']		."<br>":"").
						($data['tiresulterrortext']	?$data['tiresulterrortext']	."<br>":"");
					},
				],
			]
		]);
		//Pjax::end();
	?>
</div>