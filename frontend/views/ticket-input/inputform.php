<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use conquer\select2\Select2Widget;

$this->title = Yii::t('ticketinputform','Ticket input');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo Html::beginForm(['ticket-add'],'post'); 
      echo Html::hiddenInput('tiStatus', 'DISPATCHER_ASSIGN');
      echo Html::hiddenInput('DivisionType', 1);    //0-подразделение лифта 1-ЛАС 2-из списка
                                                    //3-подразделение ВДЭС  4-ЛАС ВДЭС 5-из списка ВДЭС

?>

<h1><?= Html::encode($this->title)?></h1> 

<div class="row">

	<?php // --- Левая колонка ------------------------------------------------------------------------ ?>
	<div class="col-md-6">
      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Source')); ?>
        </div>
        <div class="col-md-5">
          <?php echo Html::dropDownList('tiSource', 'null', ['ЦДС:Телефон'=>Yii::t('ticketinputform','Phone'), 'ЦДС:Письменное обращение'=>Yii::t('ticketinputform','Written appeal'),'ЦДС:1562'=>'1562'], ['id'=>'SourceSelect','class'=>'form-control']); ?> 
        </div>
      </div>


      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Object')); ?>
        </div>
        <div class="col-md-5">
          <?php $tiobj = is_null($_SESSION['InputTicketSelectObject'])?"001":$_SESSION['InputTicketSelectObject'] ;?>
          <?php echo Html::dropDownList('tiObject', $tiobj, ArrayHelper::map($model->tiObjects,'tiobjectcode','tiobject'),['id'=>'ObjectsSelect','class'=>'form-control','onChange'=>'onSelectObject()']); ?> 
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php //echo Html::label(Yii::t('ticketinputform','Problem')); ?>
          <?php echo Html::label("Причина<br>обращения"); ?>
        </div>
        <div class="col-md-8" id='divProblemSelect' onChange='onSelectProblem()'>
          <?php //echo Html::dropDownList('tiProblem', 'null', ArrayHelper::map($model->tiProblems,'tiproblemtypecode','tiproblemtypetext'),['id'=>'ProblemSelect','class'=>'form-control']); ?> 
          <?php echo $model->getProblemsList($tiobj); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Details')); ?>
        </div>
        <div class="col-md-10">
          <?php echo Html::textarea('tiProblemDetails','',['id'=>'tiProblemDetailsInput','style'=>"resize: none",'class'=>'form-control', 'rows'=>1, 'maxlength' => 150]); ?> 
        </div>
      </div>

      <br>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Caller')); ?>
        </div>
        <div class="col-md-10">
          <?php echo Html::input('text','tiCaller','',['id'=>'ticallerInput','class'=>'form-control', 'placeholder'=>Yii::t('ticketinputform','Full name')]); ?> 
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Phone')); ?>
        </div>
        <div class="col-md-6">
          <?php echo Html::input('text','tiCallerPhone','',['id'=>'tiCallerPhoneInput','class'=>'form-control']); ?> 
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Addres')); ?>
        </div>
        <div class="col-md-10">
          <?php echo Html::input('text','tiCallerAddres','',['id'=>'tiCallerAddresInput','class'=>'form-control']); ?> 
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Comment')); ?>
        </div>
        <div class="col-md-10">
          <?php echo Html::textarea('tiComment','',['id'=>'tiCommentInput','style'=>"resize: none",'class'=>'form-control', 'rows'=>5, 'maxlength' => 512]); ?> 
        </div>
      </div>
	</div>

	<?php // --- Правая колонка ------------------------------------------------------------------------ ?>
	<div class="col-md-6">

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Region'),NULL,['id'=>'labelRegion']); ?>
        </div>
        <div class="col-md-8">
          <?php $tireg = is_null($_SESSION['InputTicketSelectRegion'])?'null':$_SESSION['InputTicketSelectRegion'] ;?>
          <?php echo Html::dropDownList('tiRegion', 'null', ArrayHelper::map($model->tiRegions,'districtcode','districtname'), ['id'=>'tiRegionSelect','class'=>'form-control','onChange'=>'onSelectRegion()']); ?> 
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Street')); ?>
        </div>
        <div class="col-md-8">
          <?php 
          echo   Select2Widget::widget(
                [
                  'id' => 'tiStreetSelect',
                  'name' => 'tiStreet',
                  'settings' => [ 'width' => '100%' ],                 
                  'events' => [ 'select2:select' =>'onSelectStreet' ],
                ]
            );            
          ?>
          <?php// echo Html::dropDownList('tiStreet', 'null', ['улица1','улица2'], ['id'=>'tiStreetSelect','class'=>'form-control']); ?> 
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Building')); ?>
        </div>
        <div class="col-md-3">
          <?php 
          echo   Select2Widget::widget(
                [
                  'name' => 'tiFacility',
                  'id' => 'tiFacilitySelect',
                  'settings' => [ 'width' => '100%' ],                 
                  'events' => [ 'select2:select' =>'onSelectFacility'],
                ]
            );            
          ?>
        </div>
      </div>

      <div class="row" id='divEntrance'>
        <div class="col-md-2" >
          <?php echo Html::label(Yii::t('ticketinputform','Entrance')); ?>
        </div>
        <div class="col-md-2" id='divEntranceInput'>
          <?php echo Html::input('text','tiEntrance','',['id'=>'tiEntranceInput','class'=>'form-control']); ?> 
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Floor')); ?>
        </div>
        <div class="col-md-2">
          <?php echo Html::input('text','tiFloor','',['id'=>'tiFloorInput','class'=>'form-control']); ?> 
        </div>
      </div>

      <div class="row" id='divElevatorSelectRow'>
        <div class="col-md-2" id='divElevatorSelectCaption'>
          <?php echo Html::label(Yii::t('ticketinputform','Elevator')); ?>
        </div>
        <div class="col-md-6" id='divElevatorSelect'>
          <?php // echo Html::dropDownList('tiElevator', 'null', ['улица1','улица2'], ['id'=>'tiElevatorSelect','class'=>'form-control']); ?> 
        </div>
      </div>

      <div class="row" id='divApartment'>
        <div class="col-md-2">
          <?php echo Html::label(Yii::t('ticketinputform','Apartment')); ?>
        </div>
        <div class="col-md-2">
          <?php echo Html::input('text','tiApartment','',['id'=>'tiApartmentInput','class'=>'form-control']); ?> 
        </div>
      </div>

      <br>
      <div class="row" id='divTicketsList' >
        <div class="col-md-12" >
      	  <?php //Здесь будет отображаться перечень заявок по лифту?>
      	</div>
      </div>
	</div>

</div>

<?php // --- Выбор исполнителя ------------------------------------------------------------------------ ?>
<br>
<div class="row">
	<?php // Выбор приоритета заявки  ?>
	<div class="row">
        <div class="col-md-1">
          <?php echo Html::label(Yii::t('ticketinputform','Priority')); ?>
        </div>
        <div class="col-md-2">
          <?php echo Html::dropDownList('tiPriority', 
                                        'null', 
                                        [ 'NORMAL'=>Yii::$app->params['TicketPriority']['NORMAL'],
                                          'CONTROL1'=>Yii::$app->params['TicketPriority']['CONTROL1'],
                                          'CONTROL2'=>Yii::$app->params['TicketPriority']['CONTROL2'],
                                          'EMERGENCY'=>Yii::$app->params['TicketPriority']['EMERGENCY'],
                                        ],
                                        ['id'=>'PrioritySelect','class'=>'form-control','onChange'=>'onSelectPriority()']); 
          ?> 
        </div>
    </div>      

	<?php // Выбор исполнителя  ?>

      <div class="row" >
        <div class="col-md-1">
          <?php echo Html::label(Yii::t('ticketinputform','Executant'),NULL,['id'=>'labelExecutant']); ?>
        </div>

       	<?php // --- Список лифтовых ЛАСовцев  ?>
		<div class="col-md-4" id="divExecutantLas" hidden>
          <?php 
             echo Select2Widget::widget(
                [
                  'id' => 'tiExecutantSelect',
                  'name' => 'tiExecutant',
                  'items'=> ArrayHelper::map($model->tiExecutantsLas, 'id','text'),
                  'settings' => [ 'width' => '100%' ],                 
                  'events' => [ 'select2:select' =>'onSelectExecutant'],
                ]
          );      
          
          ?>
        </div>
        <div class="col-md-4" id="divExecutantDep" hidden>
        </div>
        <div class="col-md-4" id="divExecutantDepsList" >
          <?php echo Html::dropDownList('tiDepSelect','null',ArrayHelper::map($model->tiDepsList,'id','divisionname'),['id'=>'tiDepSelect','class'=>'form-control', 'onChange'=>'onSelectExecutant()']); ?>
        </div>

        <div class="col-md-4" id="divVDESExecutantLas" hidden>
          <?php 
             echo Select2Widget::widget(
                [
                  'id' => 'tiVDESExecutantSelectt',
                  'name' => 'tiVDESExecutant',
                  'items'=> ArrayHelper::map($model->getExecutantsListVDESForLAS(), 'id','text'),
                  'settings' => [ 'width' => '100%' ],    
                  'events' => [ 'select2:select' =>'onSelectExecutant'],
                ]
          );      
          ?>
        </div>
        <div class="col-md-4" id="divVDESExecutantDepsList" hidden>
          <?php echo Html::dropDownList('tiVDESDepSelect','null',ArrayHelper::map($model->getDivisionsListVDESForMaster(),'id','divisionname'),['id'=>'tiVDESDepSelect','class'=>'form-control','onChange'=>'onSelectExecutant()']); ?>
        </div>
        <div class="col-md-4" id="divDispExecutantDepsList" hidden>
          <?php echo Html::dropDownList('tiDispDepSelect','null',ArrayHelper::map($model->tiDispDepsList,'id','divisionname'),['id'=>'tiDispDepSelect','class'=>'form-control', 'onChange'=>'onSelectExecutant()']); ?>
        </div>
        <div class="col-md-4" id="divNoExecutantWarning" style="color:red;" hidden>
          Необходимо выбрать подразделение!
        </div>
      </div>

</div>

<?php // --- Кнопка "отправить" ------------------------------------------------------------------------ ?>
<br>
<div class="row">
  <div class="col-md-offset-1">
    <?php echo Html::submitButton(Yii::t('ticketinputform','Transfer to LAS'),['id' => 'SubmitButton', 'class'=>'submit btn btn-success']); ?>
  </div>
</div>

<?php 		// Подключаем нужные скрипты 
	
	//--- Инициализация JS переменных ---

	// Адреса контроллеров лоя AJAX
	$script =  "var tiajx_addr1='".Url::toRoute(["get-streets-list"])."';";
	$script .= "var tiajx_addr2='".Url::toRoute(['get-facility-list'])."';";
	$script .= "var tiajx_addr3='".Url::toRoute(["get-problems-list"])."';";
	$script .= "var tiajx_addr4='".Url::toRoute(["get-elevators-list"])."';";
	$script .= "var tiajx_addr5='".Url::toRoute(["get-elevator-division"])."';";
	$script .= "var tiajx_addr6='".Url::toRoute(["get-entrance-with-elevators"])."';";
	$script .= "var tiajx_addr7='".Url::toRoute(["get-elevator-tickets-list"])."';";

	// Названия поля ввода лифта/щита
	$script .= "var tivar_strElCapElevator ='".Html::label(Yii::t('ticketinputform','Elevator'))."';";
	$script .= "var tivar_strElCapPanel ='". Html::label(Yii::t('ticketinputform','Panel'))."';";

	// Названия для кнопки отправки
	$script .= "var tivar_strBttnCapLas = '".Yii::t('ticketinputform','Transfer to LAS')."';";
	$script .= "var tivar_strBttnCapMaster = '".Yii::t('ticketinputform','Transfer to mster')."';";

	// Занчение района по умолчанию
	$script .= "var tivar_RegionDefault='".$_SESSION['InputTicketSelectRegion']."';";
	//$script .= "var tivar_RegionDefault='".'5'."';";	// ! ! ! Для отладки

	//--- Пдключение скриптов ---
	$this->registerJs($script, yii\web\View::POS_BEGIN);
	$this->registerJsFile('js/ticketinputform.js');
	$this->registerJs("Initialization();", yii\web\View::POS_LOAD);
?>


