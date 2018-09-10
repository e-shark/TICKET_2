<?php

/* @var $this yii\web\View */
/*
 * It's an example code for 2 methods of passing parameters to view (se TicketsController.php):
 *	1. Push method:	using push we're getting $tilist1 and $provider here
 *	2. Pull method:	using pull we're getting $here tilist2
 */
//use Yii;
use yii\helpers\Url;
use yii\grid\GridView;

?>
    <?php 
   	 	$isUFitter=$model->isUserFitter();
		$tiColumns = [
			[
				'label'=>'Пр.', 
				'attribute' => 'tipriority',
				'content' => function($data){
					switch($data['tipriority']){
						case 'NORMAL': return '-';
						case 'EMERGENCY':return '<span class="glyphicon glyphicon-exclamation-sign" style="color:red"></span>';
						case 'CONTROL1':return '<span class="glyphicon glyphicon-exclamation-sign" style="color:red">1</span>';
						case 'CONTROL2':return '<span class="glyphicon glyphicon-exclamation-sign" style="color:red">2</span>';
					}
				},
				 //'value' => "ggg",//($model->ticket['tipriority']=='NORMAL')?'N':'H',
				// 'contentOptions' => ['class'=> ($data['tipriority']=='NORMAL')?"glyphicon glyphicon-eye-open":""]
			],
			[	
				'label'=>'Номер',
				'attribute' => 'ticode', 
				'content' => function($data){ 
					$cremote = $data['ticoderemote'];
					$colremote = (FALSE!==strpos($data['ticalltype'],"1562"))?'#E9967A':'grey';
					$url = Url::toRoute(['tickets/view', 'id' => $data['id']]);
					return  "<a href=$url>".$data['ticode'].'</a>'.($cremote?" <span class='glyphicon glyphicon-link' style='color:$colremote;vertical-align:super;font-size:80%'></span><br><span style='font-weight:normal;font-size:11px;color:$colremote'>$cremote</span>":'') ;},
				'format'		=>'html'
		    ],
		];
		if(!$isUFitter)array_push($tiColumns, 
			[
				'label'=>'Дата создания',
				'attribute' => 'tiopenedtime',
				'content' => function($data){ return date("d-m-Y",strtotime($data['tiopenedtime']));},	
			]
		);
		array_push( $tiColumns, 
			[
				'label'=>'Срок устранения',
				'attribute' => $isUFitter?'tiiplannedtime':'tiplannedtimenew',
				'content' => function($data)use($isUFitter){ return date("d-m-Y",strtotime($data[$isUFitter?'tiiplannedtime':'tiplannedtimenew']));},
				'contentOptions'=> function($data){ return ( !strpos($data['tistatus'],'COMPLETE') && strtotime($data['tiplannedtimenew']) < time() ) ? ['style'=>'color:red']:[];},
			]
		);
				//['attribute' => 'tistatustime','label'=>'Дата статуса'],
		array_push($tiColumns,
			[
				'label'=>'Адрес', 
				'attribute' => 'tiaddress',
				'format'=>'html',
				'content'=>function($data){ $addressStr=
					((1==$data['tiobject_id']) ? "<span style='color:#228B22 ' class='glyphicon glyphicon-resize-vertical'>":
					((2==$data['tiobject_id']) ? "<span style='color:#FF4500 'class='glyphicon glyphicon-flash'>":
					((3==$data['tiobject_id']) ? "<span style='color:#4169E1 'class='glyphicon glyphicon-phone'>":"")))
					.mb_substr($data['tiregion'],0,3).'.'
					."</span>&nbsp".$data['tiaddress'];
		            if(!empty($data['svcdesk']))$addressStr.='<br><span style="font-size:80%">'.$data['svcdesk'].'</span>';
					return $addressStr;
				},
					'contentOptions' => ['style' => 'min-width: 280px; white-space: normal;'],
			]
		);
		if( !$isUFitter ) {
			array_push( $tiColumns, [	// In-Operation status (separate column),180605,vpr
					'label'=>'Статус оборуд.',
					'format'=>'html',
					'content' => function($data){ 
						$inOos=$data['tiopstatus']; // Is the elevator in OOS now?
						$ooscolor=($inOos=='1')?'green':(($inOos=='0')?'red':'brown');
						$oosstatustext=($inOos=='1')?'РАБОТАЕТ':(($inOos=='0')?'НЕ РАБОТАЕТ':'НЕ ОПРЕДЕЛЕНО');
						return '<b><span style="color:'.$ooscolor.'">'.$oosstatustext.'</span></b>';
					},]);
			array_push( $tiColumns, [
					'label'=>'Статус',
					'format'=>'html',
					'attribute' => 'tistatus',		
					'content' => function($data){ 
						if(!empty($data['tioosbegin'])){	 // Is the elevator now or had been before in the state of OOS ?
							$inOos=empty($data['tioosend']); // Is the elevator in OOS now?
							$ooshours = intval( ((empty($data['tioosend'])?time():strtotime($data['tioosend'])) - strtotime($data['tioosbegin']))/3600 );
							if((!$inOos AND ($ooshours<24)))unset($ooshours); // Do not show OOS infos if OOS-time < 24h
						}
						return 
						//'<img src='.(($data['tiopstatus']=='0')?"/img/red_light-24.ico":(($data['tiopstatus']=='1')?"/img/green_light-24.ico":("/img/yellow_light-24.ico"))).'> '. // In-Operation status,180605,vpr
						Yii::$app->params['TicketStatus'][ $data['tistatus' ]].	// Status
						(empty($data['tistatusremote']) ? '' :							// Remote Status (1562) 
							('<br><span style="font-weight:normal;font-size:11px;color:#E9967A">1562: '.$data['tistatusremote'].'</span>')).
						((!$ooshours) ? '' :												// OOS infos
							('<br><span style="font-weight:bold;color:#9F0000">Часов в простое: '.$ooshours.'. '.($inOos?'ОТКЛЮЧЕН':'РАБОТАЕТ').'</span>'));
					},
					'contentOptions'=> function($data){return 
						('MASTER_COMPLETE'== $data['tistatus'] ) ? ['style'=>'background-color:lightgreen']:
						(strpos($data['tistatus'],'REFUSE' ) ? ['style'=>'background-color:yellow']:
						(in_array($data['tistatus'],['DISPATCHER_COMPLETE','OPERATOR_COMPLETE','1562_COMPLETE','KAO_COMPLETE']) ? ['style'=>'background-color:lightgreen']:
						(strpos($data['tistatus'],'REASSIGN' ) ? ['style'=>'background-color:red;color:white']:[])));
					}
					//'value'=>//$statustxt[$data['tistatus']]
			]);
			array_push( $tiColumns, [
					'attribute' => 'executant',	
					'label'=>'Исполнитель',
					//'content'=>function($data){return $data['executant'];} 
					//'content'=>function($data){return $data['executant'].($data['tiexecutantread']=='1'?' <span class="glyphicon glyphicon-ok" style="color:green"></span> ':
		            //(isset($data['tiexecutant_id'])?' <span class="glyphicon glyphicon-envelope" style="color:red"></span> ':'-'));}
		            'content'=>function($data){ $executantStr=(!isset( $data['tiexecutant_id'] ))?'-':
		            	($data['executant'].($data['tiexecutantread']=='1'?' <span class="glyphicon glyphicon-folder-open" style="color:green"></span> ':' <span class="glyphicon glyphicon-envelope" style="color:red"></span> '));
		            		if(!empty($data['executantdesk']))$executantStr.='<br><span style="font-size:80%">'.$data['executantdesk'].'</span>';
		            		return $executantStr;
		            }
	            ]);
			//if(isset($model->datefrom))
				array_push( $tiColumns, [	// show only for reports
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
					'contentOptions' => ['style' => 'min-width: 350px; white-space: normal;'],
			]);
		}
		array_push( $tiColumns, ['class' => 'yii\grid\ActionColumn', 'template' => '{view}','controller'=>'tickets']);
		
    	echo GridView::widget([
			'dataProvider' => $provider,
			'columns' => $tiColumns
		]);
	?>

    <?php/*<code><?= __FILE__ ?></code>*/?>
