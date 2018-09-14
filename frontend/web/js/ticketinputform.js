
	var elevator_needed = false;	// требуется выбрать лифт
	var panel_needed = false;		// требуется выбрать щитовую
	var apartment_needed = false;	// требуется указать номер квартиры
	var DivId = null;				// ID подразделения, к которому относится выбранный лифт или щитовая

	var ExecutantType = 1;			// тип исполнителя 
									// 0 - задан (из перечня подразделений лифтеров) по ID подразделения оборудоавния  
									// 1 - выбран из списка ЛАС лифтеров
									// 2 - выбран из списка подразделений лифтеров
									// 3 - задан (из перечня подразделений ВДЭС) по ID подразделения оборудоавния (пока не привязаны в базе)
									// 4 - выбран из списка ЛАС ВДЭС 
									// 5 - выбран из списка подразделений выбран из списка подразделений ВДЭС 
									// 6 - выбран из списка диспетчеров

	console.log("script started");

//----------------------------------------------------------------
// Инициализация страницы
function Initialization()
{
	$("#tiRegionSelect").val(tivar_RegionDefault);
	onSelectRegion();
	CheckElevatorInputNeeded();
	WhichExecutantNeeded();	
	FromValidate();
}


//----------------------------------------------------------------
// Вызывается при выборе объекта заявки (Лифт/ВДЭС/Домофон и т.д.)
function onSelectObject() {
    $.ajax({
         url: tiajx_addr3,
         type: "POST",
         dataType: "json",
         data: {ObjectId: $("#ObjectsSelect").val()},
         success: function(data) {
              $("#divProblemSelect").html(data);
              onSelectProblem();
              onSelectFacility();
         },
         error:   function() {
              $("#divProblemSelect").html('AJAX error!');
         }
    });
    return false;
}

//----------------------------------------------------------------
function onSelectProblem() {
    CheckElevatorInputNeeded();
    FromValidate();
    return true;
}

//----------------------------------------------------------------
// При выборе района
// обновляется список улиц
function onSelectRegion() {
    $.ajax({
         url: tiajx_addr1,
         type: "POST",
         dataType: "json",
         data: {District: $("#tiRegionSelect").val()},
         success: function(datamas) {
                $("#tiStreetSelect").html("");
                $("#tiStreetSelect").select2({data:datamas, width:'100%'});
                onSelectStreet();
         },
         error:   function() {
                $("#tiStreetSelect").html('AJAX error!');
         }

  });
  return false;
}

//----------------------------------------------------------------
// При выборе улицы
// обновляется список улиц
function onSelectStreet() {
    $.ajax({
         url: tiajx_addr2,
         type: "POST",
         dataType: "json",
         data: {StreetId: $("#tiStreetSelect").val()},
         success: function(datamas) {
                $("#tiFacilitySelect").html("");
                $("#tiFacilitySelect").select2({data:datamas, width:'100%'});
                onSelectFacility();
         },
         error:   function() {
                $("#tiFacilitySelect").html('AJAX error!');
         }

  });
  return false;
}

//----------------------------------------------------------------
function onSelectFacility() {
  EntranceSelectUpdate();
}

//----------------------------------------------------------------
function onSelectEntrance() {
  ElevatorSelectUpdate();
}

//----------------------------------------------------------------
function onSelectElevator(){
	if ( (null != $("#tiElevatorSelect").val()) && ( "" != $("#tiElevatorSelect").val()) && ( 0 != $("#tiElevatorSelect").val()) ) {
	}
	GetElevatorTicketsList();
	GetElevatorDivision();
	FromValidate();
}

//----------------------------------------------------------------
function onSelectPriority() {
  FromValidate();
}

//----------------------------------------------------------------
function onSelectExecutant() {
  FromValidate();
}

//----------------------------------------------------------------
// Определяет, нужно ли выбрать лифт для заявки,
// или щитовую или номер квартиры,
// и отображает соответствующее поле
function CheckElevatorInputNeeded(){
	elevator_needed = false;
	panel_needed = false;
	apartment_needed = false;

	switch( $("#ObjectsSelect").val() ){
		case '001': 
		case '004':	elevator_needed = true; break;
		case '002': panel_needed = true; break;
		default: apartment_needed = true; 
	}

	if (elevator_needed || panel_needed) {
		$("#divElevatorSelectRow").show();
		ElevatorSelectUpdate();
	}else
		$("#divElevatorSelectRow").hide();

	if (elevator_needed)
		$("#divElevatorSelectCaption").html(tivar_strElCapElevator);

	if (panel_needed)
		$("#divElevatorSelectCaption").html(tivar_strElCapPanel);
	
	if (apartment_needed) 
		$("#divApartment").show();
	else
		$("#divApartment").hide();
}

//----------------------------------------------------------------
// выбрать пункт в выпадающем списке выбора исполнителя
// в соотвествии с ID подразделения выбранного лифта
function DoSelectDep(){
		if (elevator_needed) 
			$("#tiDepSelect").val(DivId);
		if (panel_needed) 
			$("#tiVDESDepSelect").val(DivId);
	console.log("DivId="+DivId);
	FromValidate();
}

//----------------------------------------------------------------
// Подгружает список леифтов для выбранного дома
function ElevatorSelectUpdate(){
    $.ajax({
         url: tiajx_addr4,
         type: "POST",
         dataType: "json",
         data: {
            FacilityId: $("#tiFacilitySelect").val(),
            EntranceId: $("#tiEntranceInput").val(),
            ObjectId: $("#ObjectsSelect").val()
         },
         success: function(datamas) {
                $("#divElevatorSelect").html(datamas['Elevators']);
                onSelectElevator();
         },
         error:   function() {
                $("#divElevatorSelect").html('AJAX error!');
         }

  });
}


//----------------------------------------------------------------
// обновление списка подъездов в доме
function EntranceSelectUpdate(){
	$.ajax({
	     url: tiajx_addr6,
	     data: {FacilityId: $("#tiFacilitySelect").val(), 
	            ObjectId: $("#ObjectsSelect").val()},
	     success: function(datamas) {
	            $("#divEntranceInput").html(datamas);
	            ElevatorSelectUpdate();
	     },
	     error:   function() {
	            $("#divEntranceInput").html('AJAX error!');
	     }

	});
}

//----------------------------------------------------------------
// Получить с сервера ID департамента для выбранного лифтаы
function GetElevatorDivision() {
    $.ajax({
         url: tiajx_addr5,
         type: "POST",
         dataType: "json",
         data: {ElevatorId: $("#tiElevatorSelect").val(),
                ObjectId: $("#ObjectsSelect").val()},
         success: function(datamas) {
                $("#divExecutantDep").html(datamas['DivName']);
                DivId = datamas['DivId'];
                DoSelectDep();
         },
         error:   function() {
                $("#divExecutanDep").html('AJAX error!');
         }

	});
	console.log("GetElDivision: ElevatorId="+ $("#tiElevatorSelect").val()+" ObjectId="+$("#ObjectsSelect").val()+" -> DivId="+DivId);
}

//----------------------------------------------------------------
// Получить список заявок для лифта/щита
function GetElevatorTicketsList(){

	if ( (elevator_needed || panel_needed) &&
		(null != $("#tiElevatorSelect").val()) && ( "" != $("#tiElevatorSelect").val()) && ( 0 != $("#tiElevatorSelect").val()) ) {
		$.ajax({
			url: tiajx_addr7,
			type: "POST",
			dataType: "json",
			data: {EquipmentID: $("#tiElevatorSelect").val(),
			ObjectId: $("#ObjectsSelect").val()},
			success: function(data) {
				$("#divTicketsList").html(data);
			},
			error:   function() {
				$("#divTicketsList").html('AJAX error!');
			}
		});
	}
	else $("#divTicketsList").html("");
}

//----------------------------------------------------------------
// Отобразить нужное поле выбора исполнителя
function WhichExecutantNeeded(){
	var afterhours = false;
	var date = new Date();
	var hour = date.getHours() ;
	if ((hour<8) || (hour>16)) afterhours = true;						// нерабочее время

	// Определяем какое поле ввода Executant-а нужно
	ExecutantType = 1;													// на всякий случае зададим значение по умолчанию

	switch( $("#ObjectsSelect").val() ){
		case '001': 	// объект - лифт
			if (	afterhours || 										// если внеурочное время
					( 1 == $("#ProblemSelect").val() ) ) 				// или причина - застревание с человеком
		  		ExecutantType = 1;										// то executant выбирается из списка ЛАСовцев лифтеров
			else {
				var ElevatorsNumber = 0;
				var es = document.getElementById("tiElevatorSelect");
				if (es != null) 
					ElevatorsNumber = document.getElementById("tiElevatorSelect").options.length;

		  		if (ElevatorsNumber > 0)
					ExecutantType = 0;									// то executant задан по ID подразделения, за которым закреплен лифт
			  	else
					ExecutantType = 2;									// то executant выбирается из списка подразделений
			}

		break;

		case '004':		// объект - диспетчеризация
			ExecutantType = 6;											// то executant выбирается из списка диспетчеров
		break;

						// объект - все остальное: ВДЭС, домофон, прочее
		default: 
			if (	afterhours || 										// если внеурочное время
					( 'EMERGENCY' == $("#PrioritySelect").val() ) )		// или приоритет - срочная аявка
		  		ExecutantType = 4;										// то executant выбирается из списка ЛАСовцев ВДЭС
			else 
				ExecutantType = 5;										// то executant выбирается из списка подразделений ВДЭС
	}

	// включаем соответсвующее поле ввода, остальные прячем

	if (0 == ExecutantType) $("#divExecutantDep").show();
	else $("#divExecutantDep").hide();

	if (1 == ExecutantType) $("#divExecutantLas").show();
	else $("#divExecutantLas").hide();

	if (2 == ExecutantType) $("#divExecutantDepsList").show();
	else $("#divExecutantDepsList").hide();

	//if (3 == ExecutantType) $("#").show();
	//else $("#").hide();

	if (4 == ExecutantType) $("#divVDESExecutantLas").show();
	else $("#divVDESExecutantLas").hide();

	if (5 == ExecutantType) $("#divVDESExecutantDepsList").show();
	else $("#divVDESExecutantDepsList").hide();

	if (6 == ExecutantType) $("#divDispExecutantDepsList").show();
	else $("#divDispExecutantDepsList").hide();

	// переименовываем кнопку отправки
	switch(ExecutantType){
		case 1:
		case 4: 
			$("#SubmitButton").html(tivar_strBttnCapLas); break;

		case 0:
		case 2: 
		case 3:
		case 6:
		default:
			$("#SubmitButton").html(tivar_strBttnCapMaster); break;
	} 
	

	console.log( "ExecutantType"+ ExecutantType);
}

//----------------------------------------------------------------
// Проверить, выбран ли Executant
function ValidateExecutant() {
	var res = false;
	var val = '';

	WhichExecutantNeeded();

	switch (ExecutantType) {
		case 0: val = 'ok'; break;
		case 1: val = $("#tiExecutantSelect").val(); break;
		case 2: val = $("#tiDepSelect").val(); break;
		case 3: val = 'ok'; break;
		case 4: val = $("#tiVDESExecutantSelectt").val(); break;
		case 5: val = $("#tiVDESDepSelect").val(); break;
		case 6: val = $("#tiDispDepSelect").val(); break;
	}

	res = (val != '') && (val != null);
	console.log("ExecutantType"+ ExecutantType, "val='"+val+"' res="+res);

	if (res) $("#labelExecutant").removeAttr('style','color:red;');
	else $("#labelExecutant").attr('style','color:red;');

	/*
	if (res) $("#divNoExecutantWarning").hide();
	else $("#divNoExecutantWarning").show();
	*/

	return res;
}

//----------------------------------------------------------------
function ValidateRegion()
{
	var res = true;
	res &= (null != $("#tiRegionSelect").val()) && ('' != $("#tiRegionSelect").val()) && (0 != $("#tiRegionSelect").val());

	if (res) $("#labelRegion").removeAttr('style','color:red;');
	else $("#labelRegion").attr('style','color:red;');

	console.log("ValidateRegion: val="+$("#tiRegionSelect").val()+" res="+res);
	return res;
}

//----------------------------------------------------------------
// Проверка валдности всех полей форма,
// разрешение/блокировака кнопки отправки
function FromValidate()
{
	var res = true;
	res &= ValidateExecutant();
	res &= ValidateRegion();
	if (res) $("#SubmitButton").removeAttr('disabled');
	else $("#SubmitButton").attr('disabled', 'disabled');

}
