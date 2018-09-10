<?php
use yii\helpers\Html;
use frontend\models\Tickets;


$this->title = Yii::t('app','Reports');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reports-index">
	<P>Список отчетов:</P>
	<ul>
		<?= '<li>'.Html::a('Список Заявок', ['ticketslist'] 								/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по выполнению заявок', ['titotals'] 						/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по открытым заявкам на лифты', ['oosnow','object_id' =>1] 						/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по открытым заявкам на ВДЭС', ['oosnow','object_id' =>2] 						/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по повторным заявкам', ['repfailures'] 					/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по поступлению заявок по дням', ['tiperday'] 				/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по поступлению заявок по месяцам', ['tipermonth'] 		/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Работа Аварийной Службы', ['tilas'] 							/*,[,'class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по выполнению заявок 1562', ['titotals1562']				/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Список остановленных и запущенных лифтов', ['stopped-list']		/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Количество остановленных лифтов по районам', ['stopped-sum']	/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по количеству остановленных лифтов', ['stopped-count']	/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Свод заявок 1562', ['summary1562']								/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?= '<li>'.Html::a('Отчет по простоям лифтов при ремонте', ['repairs-list'],[]		/*,['class' => 'btn btn-success']*/).'</li>' ?>
	     <?php 	if(FALSE!==Tickets::getUserOpRights()){// Reports below intended for use by organization staff only?>
		<?= '<br><li>'.Html::a('Журнал экспорта в систему ИТЕРА', ['iteralog']				/*,['class' => 'btn btn-success']*/).'</li>' ?>
		<?php }?>


	</ul>
</div>