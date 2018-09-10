<?php
use yii\helpers\Html;
use yii\helpers\Url;
/*
 *	Modal dialog window for entering the operation status of elevator by fitter after fitter arrival and initial inspection
 */
?>

<div id="md_FitterStartStop" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<center>
				<?php
				echo '<b>Адрес</b>:'.$model->ticket['tiaddress'].'<br>';
				echo '<b>Причина обращения</b>: '.$model->ticket['tiopenedtime'].' '.$model->ticket['tiproblemtypetext'].' ('.$model->ticket['tiproblemtext'].')<br>'.$model->ticket['tidescription'];
				echo '<h4>Статус лифта в системе: '.($model->ticket['tiopstatus']=='0'?'<span style="color:red">ОСТАНОВЛЕН':($model->ticket['tiopstatus']=='1'?'<span style="color:green">РАБОТАЕТ':'<span style="color:brown">НЕ ОПРЕДЕЛЕНО')).'</span></h4>';
				?>					
			</div>
			<div class="modal-body">
				<center>
				<h4 class="modal-title">Укажите статус работы лифта по приезду:</h4><br>

				<?=  Html::beginForm(['appoint','ticketId'=>$model->ticket['id']],'post');?>
    			<?= Html::hiddenInput('ticketId'    ,$model->ticket['id'])?>
    			<?= Html::hiddenInput('senderId'    ,$model->useroprights['id'])?>
    			<?= Html::hiddenInput('senderdeskId',$model->useroprights['division_id'])?>
    			<?= Html::hiddenInput('servicedeskId',$model->ticket['tidivision_id'])?>
    			<?= Html::hiddenInput('actor'       ,$model->actor )?>

				<?php

				/*--- Button 'LIFT IN NORMAL OPERATION' */
				if($model->ticket['tiopstatus']=='1')echo
					'<button class="btn btn-success " type="button" data-dismiss="modal">По приезду Лифт работает</button><br><br>';
				else echo
				 	Html::submitButton('По приезду Лифт работает',['class'=>"btn btn-success",'formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_CANCEL_OOS']) ]).'<br><br>';

				/*--- Button 'LIFT IS OUT OF SERVICE' */
				if($model->ticket['tiopstatus']=='0')echo
					'<button class="btn btn-danger" type="button"  data-dismiss="modal">По приезду Лифт НЕ работает</button>';
				else echo
				 	Html::submitButton('По приезду Лифт НЕ работает',['class'=>"btn btn-danger",'formaction'=>Url::toRoute(['appoint','tistatus'=>$model->actor.'_FORCE_OOS']) ]);
				?>
				<?= Html::endForm();?>
				
			</div>
			<div class="modal-footer">
				<center>
				<?php
				echo '<a class="btn btn-default " href='.Url::toRoute(['tickets/index']).'>Назад к списку заявок</a>';
				?>
			</div>
		</div>
	</div>
</div>
