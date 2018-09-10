<?php
namespace frontend\models;
use yii;
use yii\base\Model;
//use yii\data\SqlDataProvider;


/*
 * Helper class for interacting with 1562 system
 */
class Hlp1562 extends Model
{
	/**
	 * Engine for posting to 1562
	 * 1.Fills all actions as records to the export1562log table for further export its to external system by external  transmitter task.
	 * 2.Tries to immediately send messages to 1562
	 * @param int ticket_id - ticket.id, should be valid id of ticket , otherwise function returns FALSE
	 * @param string opstatus - the operation status string (like EXECUTANT_COMPLETE etc)
	 * @param int person_id - should be valid employee.id, or NULL, the sender id -> ruser_id
	 * @param int executant_id - should be valid employee.id, or NULL, the executant (or receiver id) ->rperformer_id
	 * @param string comment - the string with comment to operation
	 * @return mixed - FALSE on error, or int value, the export1562log.id - id of record inserted into export1562log
	 *
	 * Uses config parameters:
	 *	export1562 		= Yes/No
	 *	url1562 		= https://062.city.kharkov.ua/LIFT
	 *	url1562userpwd 	= babenko:123456
	 *	
	 */
	public static function export1562Log($ticket_id, $opstatus, $person_id,$executant_id,$comment){
		$op1562ids = array();	// array of id for records in export1562Log for $ticket_id
		//--- Get original ticket from db
		if(!$ticket_id)			return FALSE;
		if( FALSE==($dbticket=Yii::$app->db->createCommand("SELECT * FROM ticket left join oostype ON ticket.tioostype_id=oostype.id left join ticketproblemtype  ON ticket.tiproblemtype_id=ticketproblemtype.id where ticket.id=$ticket_id")->queryOne())) 	return FALSE;
		if($dbticket['ticalltype'] != '1562')return FALSE;	// process only 1562 records
		//---Look if there is a records (max 3) in export1562log for this ticket
		if(FALSE===($db1562log=Yii::$app->db->createCommand("SELECT * FROM export1562log where ticket_id=$ticket_id")->queryAll()))return FALSE;
		foreach ($db1562log as $rec1562) {
			if(		1==$rec1562['op1562']) $op1562ids[1] = $rec1562['id'];	// accept
			else if(2==$rec1562['op1562']) $op1562ids[2] = $rec1562['id'];	// complete
			else if(3==$rec1562['op1562']) $op1562ids[3] = $rec1562['id'];	// refuse
		}
		//---Look if we have already inserted record into export1562log for this event
		if($opstatus == 'DISPATCHER_COMPLETE')if(!empty($op1562ids[2]))return $op1562ids[2]; // complete record already in db, do nothing
		if($opstatus == 'DISPATCHER_REFUSE'  )if(!empty($op1562ids[3]))return $op1562ids[3]; // refuse record already in db, do nothing
		if(!empty($op1562ids[1]))return $op1562ids[1]; // accept record already in db, do nothing for all other cases

		//---Set status1562
		if( FALSE !== strpos($dbticket['tistatusremote'],'Нужно принять из 062'))$status1562=1;
		else if( FALSE !== strpos($dbticket['tistatusremote'],'Принята исп-лем'))$status1562=3;
		else $status1562=3;
		//---Set op1562
		switch($opstatus){
			case 'DISPATCHER_ASSIGN': case 'DISPATCHER_ASSIGN_MASTER': case 'DISPATCHER_ACCEPT': case 'DISPATCHER_REASSIGN':
				$op1562 = 1;	// will force card_pere_wr1.php action (accept 1562-ticket)
			break;
			case 'DISPATCHER_COMPLETE':
				$op1562 = 2;	// will force card_pere_wr2.php action (report the 1562-ticket has been completed)
			break;
			case 'DISPATCHER_REFUSE':
				$op1562 = 3;	// will force card_pere_wr3.php action (refuse  the 1562-ticket)
			break;
			default: return FALSE;
		}
		//---Set regl_time,isp_time
		$regl_time = strtotime($dbticket['tiplannedtimenew']);
		$isp_time  = time();//date("Y-m-d H:i:s");
		//---Set isp
		unset($isp);
		if( !empty($executant_id) ) if(FALSE!==($ispcard = Yii::$app->db->createCommand("SELECT * FROM employee e left join division d on e.division_id=d.id where e.id=$executant_id"))){
					if(FALSE!==strpos($ispcard['oprights'],'M'))		$isp=59;
					else if(FALSE!==strpos($ispcard['oprights'],'m'))	$isp=58;
					else if(FALSE!==strpos($ispcard['oprights'],'F'))	{
						$isp=61;
						if(FALSE!==strpos($ispcard['divisionname'],'ЛАС'))$isp=60;
					}
			}
		//---Set job
			if( 1 == $dbticket['tiopstatus']) {	// LIft is in normal operations
				if( 1 == $dbticket['ticketproblemtype.id'] ) 		$job=58;
				else if(24 == $dbticket['ticketproblemtype.id'])	$job=65;
				else if( 8 == $dbticket['ticketproblemtype.id'])	$job=62;
				else if( 9 == $dbticket['ticketproblemtype.id'])	$job=62;
				else 												$job=61;	// LIft is in normal operations
			}
			else if( 2 == $dbticket['tiopstatus']) {	// LIft is in OOS
				if( 1 == $dbticket['ticketproblemtype.id'] ) 		$job=59;
			}
			else 													$job = 67;

		//--- Set original ticket fields:
		$ticodelogged = $dbticket['ticode']; 	 // latch the original ticket number,save it for case if for some reasons (errors) it will be modified in db
		$tistatusloggedtext = Yii::$app->params['TicketStatus'][ $opstatus ];
		$ticode1562 = $dbticket['ticoderemote']; // 1562 number

		//---(1) Login to 1562
		if( FALSE!==stripos(Yii::$app->params['export1562'],'Yes' )) {
			$url1562verbs=[
				"",
				"/card_pere_wr1.php",
				"/card_pere_wr2.php",
				"/card_pere_wr3.php"
			];
			$txattempts=1;	// here we will do the first attempt to tx the record, if it fails, then external transmitter will do the further tryes
			$txcount=1;	// Will at least try to log in
			$export1562url = $txrequest = Yii::$app->params['url1562'].$url1562verbs[$op1562];
			$postdata = [
				'c'=>$dbticket['ticoderemote'],
				'Status'=> $status1562,
				'Userid'=> 167,
				'Disp'	=>  513,
				'Isp'	=> $isp,
				'Job'	=> $job,

				'd_regl'=>date("d",$regl_time),
				'm_regl'=>date("m",$regl_time),
				'y_regl'=>date("Y",$regl_time),
				'cl_regl'=>date("H",$regl_time),
				'min_regl'=>date("i",$regl_time),

				'd_isp'=>date("d",$isp_time),
				'm_isp'=>date("m",$isp_time),
				'y_isp'=>date("Y",$isp_time),
				'cl_isp'=>date("H",$isp_time),
				'min_isp'=>date("i",$isp_time),
				'Dirid'=>17,
				'pr'=>$comment
			];
			$url1562postops=[
				CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0",
				CURLOPT_USERPWD=>Yii::$app->params['url1562userpwd'],//'babenko:123456',
				CURLOPT_SSL_VERIFYPEER=>false,
				CURLOPT_SSL_VERIFYHOST=>0,
				CURLOPT_POST=>1,
				CURLOPT_POSTFIELDS=>$postdata,
			];
			$ch=curl_init($export1562url);
			curl_setopt_array($ch,$url1562postops);
			
			$txresult=curl_exec($ch);$txcount++;$txrequest.="\n".$iteraAPIurl;
			if(FALSE===$txresult)$txresult='Error:failed to POST data to 1562';
			else {
				//Yii::warning('======ITERA:'.$txresult,__METHOD__);
				$txresult=mb_convert_encoding($txresult,'UTF-8','UTF-8');
				$isexportdone=1;
			}
			//--- Loggoff from 1562
			curl_close( $ch );
		}
		//--- Write to export1562log
		Yii::$app->db->createCommand()->insert('export1562log',[
			//'recordtime'=>$recordtime,	// server will set time
			'ticket_id'=>$ticket_id,
			'ticodelogged'=>$ticodelogged,
			'tistatuslogged'=>$opstatus,
			'tistatusloggedtext'=>$tistatusloggedtext,
			'ticode1562'=>$ticode1562,
			'person_id'=>$person_id,
			'executant_id'=>$executant_id,
			
			'status1562'=>$status1562,
			'op1562'=>$op1562,
			'regl_time'=>$regl_time,
			'isp_time'=>$isp_time,
			'isp'=>$isp,
			'job'=>$job,
			'dirid'=>17,
			'pr'=>$comment,
			
			//'txtime'=>$recordtime,	// server will set time
			'txattempts'=>$txattempts,
			'txcount'=>$txcount,
			'txrequest'=>mb_substr($txrequest,0,255),
			'txresult'=>mb_substr($txresult,0,255),
			'isexportdone'=>$isexportdone,
			])->execute();
		return intval(Yii::$app->db->getLastInsertID());
	}
}