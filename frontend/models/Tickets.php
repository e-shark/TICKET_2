<?php
namespace frontend\models;
use yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use frontend\models\Report_Titotals;


/*
 * This is example code for how to get data from db:
 *	1. With createComman: see dgetTicketsList()
 *	2. With SqlDataProvider: see search()
 */
class Tickets extends Model
{
	const LASDIVISION_ID = 8;
	const LASDIVISION_CODE = 8;
	const LAS1DIVISION_CODE = 12;
	const OPERATORDIVISION_CODE = 6;

	public $ticket;			// the 1-dimention array  with current record in the ticket table
	public $respfitterId;	// Id of Fitter, responsible for selected device
	public $tilogarray;		// array with all records from ticketlog
	public $tilogprovider;	// provider for records from ticketlog
	public $tispartprovider;// array with all records from ticketlog for spare parts
	public $fitterslist;	// the 2-dimention array  with records from the employee table
	public $useroprights;	// the 1-dimention array  ['id','division_id','oprights',] with currently logged in user rights
	public $spartlist;		// the 2-dimention array  with elevator error codes
	public $uploadedfilelist;	// array of file names in uploads directory for the ticket
	
	public $hasOos;			// if TRUE, the elevator is in or was in an Out-Of-Service conditions
	public $hasOosNow;		// if TRUE, the elevator is in an Out-Of-Service conditions NOW
	public $oosHours;		// the number of hours, during wich elevator has been or is in an Out-Of-Service conditions
	
	public $openedTickets;	// Array,the list of tickets opened currently to ticket address, vpr,16.04.2018
	public $objectTicketsProvider;	// Provider,list of ALL tickets when ever opened for the address of this ticket, vpr,16.04.2018
	
	public $tilist;
	public $PartsClassList;
	public $actor;
	//---Filter for tickets list in index
	public $f_tidevicetype;
	public $district;
	public $tifindstr;
	//public $fltrDistrict;	// Filter: district for filtering

	/*---171020,did start---*/
    public static function GetPartsList($classid=0)
    {
    	if ($classid == 0) { 
            $PartsList = Yii::$app->db->createCommand('SELECT id,elspname as text, elspunit
            	                                       FROM elevatorsparepart 
        	                                           WHERE NOT (elspcode LIKE "%.0.0")
            	                                       ')->queryAll();	
    	}else{
            $PartsList = Yii::$app->db->createCommand('SELECT id,elspname as text, elspunit
            	                                       FROM elevatorsparepart 
        		                                       where (elspcode LIKE "'.$classid.'.%.%") 
        	                                             AND NOT (elspcode LIKE "'.$classid.'.0.0")
        	                                           ')->queryAll();	
    	}
    	return $PartsList;
    }
    public static function GetPartUnit($elspid=0)
    {
    	$select= Yii::$app->db->createCommand('SELECT id, elspunit FROM elevatorsparepart WHERE id ='.$elspid.' ; ' )->queryOne();	
    	if (isset($select['elspunit'])) return $select['elspunit'];
    	else return 'шт';

    }
    /*---171020,did end---*/

	public static function getCallTypesList(){
    	$calltypes = ArrayHelper::map(Yii::$app->db->createCommand('SELECT DISTINCT ticalltype FROM ticket order by ticalltype')->queryAll(),'ticalltype','ticalltype');
    	return $calltypes = [""=>'Все']+$calltypes;
    }
	public static function getDistrictsList(){
    	$districts = ArrayHelper::map(Yii::$app->db->createCommand('SELECT id,districtname FROM district where districtlocality_id=159')->queryAll(),'districtname','districtname');
    	return $districts = [""=>'Все']+$districts;
    }
	public function getTicketsList()
	{
		$tilist = Yii::$app->db->createCommand('SELECT * FROM ticket')->queryAll();
		//Yii::warning($tilist,__METHOD__);
		return $tilist;
	}	
	public static function getMonthsList($all=false) {
		return ((FALSE===$all)?[]:[0=>'Все']) + [1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',10=>'10',11=>'11',12=>'12'];
	}
	public static function getYearsList($all=false) {
		$years = array();
		$yearfrom = Yii::$app->db->createCommand("SELECT MIN(YEAR(tiopenedtime)) as y FROM ticket;" )->queryOne()['y'];	
		$yearto = date('Y');
		if(empty($yearfrom))$yearfrom=$yearto;
		for($i=$yearfrom;$i<=$yearto;$i++)$years[$i]=$i;
		return ( (FALSE===$all) ? [] : [0=>'Все'] ) + $years;
	}
	public static function getDeviceTypesList(){
    	$devicelist = ArrayHelper::map(Yii::$app->db->createCommand('SELECT id,tiobject FROM ticketobject')->queryAll(),'id','tiobject');
    	return $devicelist = ["0"=>'Все']+$devicelist;
	}
    /**
	 * Gets all records from ticket db table, gets rights for currenly logged in user
	 * @param boolean $ticketsFilterAll - filtering condition
	 */
	public function search($ticketsFilterAll,$params)
	{
		//--- Filter
		if(empty($params['district']))$params['district']=$this->district;
		if(empty($params['f_tidevicetype']))$params['f_tidevicetype']=$this->f_tidevicetype;
		if(empty($params['tifindstr']))$params['tifindstr']=$this->tifindstr;
		$f1sql = Report_Titotals::fillparamsfiltet1($this,$params);

		$tiplannedtime = $this->isUserMaster() ? 'tiplannedtimenew':'tiiplannedtime';
		$udeskid = $this->useroprights['division_id'];	// DeskId for currently logged in user
		//$sqltext='SELECT ticket.id as id, tipriority, ticode, tistatus, tiplannedtime,tiaddress, CONCAT(lastname," ", firstname) as executant FROM ticket left join employee on employee.id=tiexecutant_id';
		$sqltext='SELECT ticket.*, CONCAT(COALESCE(lastname,"")," ", COALESCE(firstname,"")) as executant,d.divisionname as executantdesk,d1.divisionname as svcdesk,oostype.oostypetext,ticketproblemtype.tiproblemtypetext FROM ticket left join employee on employee.id=tiexecutant_id left join ticketproblemtype on ticketproblemtype.id=ticket.tiproblemtype_id left join oostype on oostype.id=ticket.tioostype_id left join division d on d.id=tidesk_id left join division d1 on d1.id=tidivision_id';
		
		//---Prepare the sql statement for tickets according to the user rights
		if( $this->isUserMaster() ) {
			if($this->isUserHMaster()) {	// 180524,vpr, show all tickets served by fitters of all Masters, who slaved to Head Master
            	if( 0 < count($subdivs=array_column(Yii::$app->db->createCommand("SELECT id FROM division WHERE division_id=$udeskid")->queryAll(),'id'))) {
            		$subdivsStr = implode( ",", $subdivs);
            		$sqltext1 = " OR (ifnull(tidivision_id,0) in ($subdivsStr)) OR (ifnull(tidesk_id,0) in ($subdivsStr))";
            	}
            	else unset($sqltext1);
			}
			$sqltext = $sqltext." where (($udeskid in (tidesk_id,tidivision_id) $sqltext1) and (ifnull(tidesk_id,0)!=".self::OPERATORDIVISION_CODE."))";
			if( !$ticketsFilterAll) $sqltext = $sqltext.' and tistatus not in ("MASTER_COMPLETE", "DISPATCHER_COMPLETE", "OPERATOR_COMPLETE", "1562_COMPLETE", "KAO_COMPLETE", "MASTER_REFUSE")';
		} 
		else if( $this->isUserDispatcher() || $this->isUserOperator()) { 
			//---V.0: Filter for all cards where current user desk is originator or executor one
			//$sqltext = $sqltext." where ($udeskid in (tioriginatordesk_id,tidesk_id))";
			//---V.1: Filter for all cards where current user desk is DISPATCHER; OR is OPERATOR and is originator or executor one
			$sqltext = $sqltext." where ticode like '%'";
			if($this->isUserOperator())$sqltext = $sqltext.' and (tioriginatordesk_id='.$this->useroprights['division_id'].' OR tidesk_id='.$this->useroprights['division_id'].')';
			else if($this->isUserDispatcher())$sqltext = $sqltext."and (ifnull(tidesk_id,0)!=".self::OPERATORDIVISION_CODE.")";	// 180529, vpr
			//---V.2: Filter for all cards where current user desk is originator or executor or recipient in ticket history one
			//$sqltext = $sqltext." where (($udeskid in (tioriginatordesk_id,tidesk_id)) OR $udeskid in (select tilreceiverdesk_id from ticketlog where tilticket_id=ticket.id)  )";
			//---Filter for {all} | {not completed}
			if( !$ticketsFilterAll) $sqltext = $sqltext.' and tistatus not in ("DISPATCHER_COMPLETE","OPERATOR_COMPLETE","1562_COMPLETE","KAO_COMPLETE")';
			//---Filter for district
			//if($this->isUserDispatcher() && (!empty($this->fltrDistrict)))	$sqltext = $sqltext." and tiregion like \"$this->fltrDistrict\"";
			if($this->isUserDispatcher() )	$sqltext = $sqltext.$f1sql;
		} 

		else if( $this->isUserFitter() ) {
			$sqltext = $sqltext.' where tiexecutant_id='.$this->useroprights['id']. " and tistatus not like '%COMPLETE%' and tistatus not like '%REFUSE%'";
		}
		else 	// Guest
			if( !$ticketsFilterAll)$sqltext = $sqltext." where  tistatus not in ('MASTER_COMPLETE', 'DISPATCHER_COMPLETE', 'OPERATOR_COMPLETE', '1562_COMPLETE', 'KAO_COMPLETE')";
		
		$provider = new SqlDataProvider([
			'sql' => $sqltext,
			'key' => 'id',
			'sort' => [
				'attributes' => [
					'tipriority',
					'ticode',
					'tistatus',
					'tistatustime',
					//$tiplannedtime,
					'tiaddress'=>[
						'asc'=>['tiregion'=>SORT_ASC,'tiaddress'=>SORT_ASC],
						'desc'=>['tiregion'=>SORT_DESC,'tiaddress'=>SORT_DESC],
					],
					'executant'
				],
				'defaultOrder' => [ 'ticode' => SORT_DESC ],
			],
		]);
		return $provider;
	}
	/**
	 * Gets an only separate record from ticket table
	 * @param integer $id - key for current record from ticket table
	 * @return The Tickets model instance with filled members for ticket itself, user who logged in, and fitters of the user's department
     */
	public function findOne($id)
	{
		//---Get records from ticket for given ticket
		if(!isset($this->ticket)){
			$sql4ticket=
			'SELECT ticket.*, tiobject, ticketobject.tiobjectdevicetype as devicetypecode,tiproblemtypetext,d.divisionname as divisionname,d1.divisionname as deskname,d2.divisionname as originatordeskname,d3.divisionname as executantdeskname,CONCAT(lastname," ",coalesce(firstname,"")," ",coalesce(patronymic,"")) as executant, oostypetext, c1.companyname as eqocompany,c2.companyname as eqscompany,c3.companyname as eqsubscompany
			from ticket 
				left join ticketobject on ticketobject.id=tiobject_id 
				left join ticketproblemtype on ticketproblemtype.id=tiproblemtype_id 
    			left join employee on employee.id=tiexecutant_id
    			left join division d on d.id=tidivision_id 
    			left join division d1 on d1.id=tidesk_id 
    			left join division d2 on d2.id=tioriginatordesk_id 
    			left join division d3 on d3.id=employee.division_id 
    			left join elevator el on el.id=tiequipment_id
    			left join company c1 on c1.id=elownercompany_id
    			left join company c2 on c2.id=elservicecompany_id
    			left join company c3 on c3.id=elsubservicecompany_id
    			left join oostype on tioostype_id=oostype.id where ticket.id='.$id; 

    		$this->ticket = Yii::$app->db->createCommand($sql4ticket)->queryOne();

			//--- Get list of tickets, opened when ever for the address of this ticket , vpr, 16.04.2018
	    	$objectTickets=Yii::$app->db->createCommand('SELECT ticket.*,ticketproblemtype.tiproblemtypetext,oostype.oostypetext FROM ticket left join ticketproblemtype on ticketproblemtype.id=ticket.tiproblemtype_id left join oostype on oostype.id=ticket.tioostype_id WHERE (tiobjectcode is not null) and (tiobjectcode!="")  and (tiobjectcode = :tiobjectcode) and (tiobject_id=:tiobject_id) order by id desc')
	    	->bindValues([':tiobjectcode'=>$this->ticket['tiobjectcode']])
	    	->bindValues([':tiobject_id'=>$this->ticket['tiobject_id']])
	    	//->bindValues([':ticode'=>$this->ticket['ticode']])
	    	->queryAll();
	    	$this->objectTicketsProvider = new ArrayDataProvider([
	    		'allModels' => $objectTickets,
	    		'pagination' => [ 'pageSize' => 50,	],
	    	]);
	    	//--- Get only tickets, opened at present time
	    	unset($itsme);
	    	foreach($objectTickets as $ti)if(!in_array($ti['tistatus'],["OPERATOR_COMPLETE","DISPATCHER_COMPLETE","KAO_COMPLETE","1562_COMPLETE"])){
	    		if($this->ticket['ticode'] != $ti['ticode'])$this->openedTickets[]=$ti;
	    		else $itsme=$ti;
	    	}
	    	if(!empty($itsme))$this->openedTickets[]=$itsme;// place the ticket itself to the end position of array

			//---Get list of classes of parts // получить классификацию ремкомплекта, 171020,did
	        $this->PartsClassList = Yii::$app->db->createCommand('SELECT id,elspcode,elspname FROM elevatorsparepart WHERE elspcode LIKE "%.0.0" ')->queryAll();	
			//---Get known who is current user and take all fitters from his department
			$this->useroprights = $this->getUserOpRights();
			$this->actor = $this->getActor();
			$this->fitterslist = $this->getFittersList($this->useroprights['division_id'],$this->ticket['devicetypecode']);

    		
    		//--- Get right fitter for the list, 180328,vpr
    		$this->respfitterId = $this->ticket['tiexecutant_id'];	// executor already set, use it
    		if( empty($this->respfitterId) ) if( $this->actor == 'MASTER' ){	
    			$this->respfitterId = Yii::$app->db->createCommand("SELECT elperson_id from elevator where elinventoryno like \"".$this->ticket['tiobjectcode']."\"")->queryOne()['elperson_id'];
    		}
    		//--- Calculate OOS parameters
    		$this->hasOos = !empty($this->ticket['tioosbegin']);
			$this->hasOosNow = ( $this->hasOos && empty($this->ticket['tioosend']));
			if($this->hasOosNow)$this->oosHours = intval((time() - strtotime($this->ticket['tioosbegin'])) / 3600);
			else if($this->hasOos) $this->oosHours = intval((strtotime($this->ticket['tioosend']) - strtotime($this->ticket['tioosbegin'])) / 3600);
    	}

		//---Get all records from ticket log
		if(!isset($this->tilogprovider)){
			$sql4tilog = 
			'SELECT ticketlog.*, CONCAT(e1.lastname," ",e1.firstname) as sender,d1.divisionname as senderdesk, CONCAT(e2.lastname," ",e2.firstname) as receiver,d2.divisionname as receiverdesk  FROM ticketlog 
					left join employee e1 on e1.id=tilsender_id 
					left join employee e2 on e2.id=tilreceiver_id
					left join division d1 on d1.id=tilsenderdesk_id
					left join division d2 on d2.id=tilreceiverdesk_id where (tiltype="WORKORDER" or tiltype="SVCORDER") and tilticket_id='.$id.' order by id desc';
			$this->tilogarray=Yii::$app->db->createCommand($sql4tilog)->queryAll();
			$this->tilogprovider =new ArrayDataProvider([
				'allModels' => $this->tilogarray,
				'key' => 'id',
				'sort' => [
					'attributes' => [
						'tiltime',
					],
					'defaultOrder' => [ 'tiltime' => SORT_DESC ],
				],
			]);
		}
		//---Get all from spare part records
		if(!isset($this->tispartprovider)){
			$sql4tispart = 
			'SELECT ticketlog.*, CONCAT(e1.lastname," ",e1.firstname) as sender,d1.divisionname as senderdesk, CONCAT(e2.lastname," ",e2.firstname) as receiver,d2.divisionname as receiverdesk  FROM ticketlog 
					left join employee e1 on e1.id=tilsender_id 
					left join employee e2 on e2.id=tilreceiver_id
					left join division d1 on d1.id=tilsenderdesk_id
					left join division d2 on d2.id=tilreceiverdesk_id where tiltype="SPORDER" and tilticket_id='.$id;
			$this->tispartprovider = new SqlDataProvider([
				'sql' => $sql4tispart,
				'key' => 'id',
				'sort' => [
					'attributes' => [
						'tiltime',
					],
					'defaultOrder' => [ 'tiltime' => SORT_DESC ],
				],
			]);
			//$this->ticketlog = Yii::$app->db->createCommand($sql4tilog)->queryAll();
		}
		//---Get spare part catalog
		if(!isset($this->spartlist)){
			$this->spartlist = Yii::$app->db->createCommand('SELECT id,CONCAT(IFNULL(elspcode,"")," ",elspname) as elspart,elspunit FROM elevatorsparepart')->queryAll();	
		}
		//---Get all uploaded files for the ticket
		if(!isset($uploadedfilelist)){
			$this->uploadedfilelist=UploadImage::getUploadedFileList($this->ticket['ticode'].'*');
		}
		return  $this;
	}
	/**
     *  Gets the info on rights, id and division for currently logged in user
     * @return mixed, array ['id','division_id','oprights','divisioncodesvc'] if user is logged in and have a rights for some operations, boolean FALSE otherwise
     *
     */
    public static function getUserOpRights()
    {
        if(Yii::$app->user->isGuest) return FALSE;	// user is not currently logged in
        if(FALSE===($orights = Yii::$app->db 	// may be FALSE, if user have not a corresponding record in employee table
        	->createCommand('SELECT e.id,e.division_id,e.oprights,d.divisioncodesvc from employee e left join division d on d.id=e.division_id where user_id=:uid')->bindValues([':uid'=>Yii::$app->user->id])
        	->queryOne()))return FALSE;
        //---produce user role name
        if(FALSE !== strpos($orights['oprights'],'D'))					$urname='Диспетчер';
        else if(FALSE !== strpos($orights['oprights'],'d'))				$urname='Оператор';
        else if(FALSE !== strpos($orights['oprights'],'T'))				$urname='Технолог';
        else if(FALSE !== strpos($orights['oprights'],'M'))				$urname='Старший Мастер';
        else if(FALSE !== strpos($orights['oprights'],'m'))				$urname='Мастер';
        else if(FALSE !== strpos($orights['oprights'],'F')){
        	if(FALSE !== strpos($orights['divisioncodesvc'],'E'))		$urname='Электромонтер';
        	else if(FALSE !== strpos($orights['divisioncodesvc'],'L'))	$urname='Электромеханик';
        }
        if((FALSE !== strpos($orights['divisioncodesvc'],'L'))&&(FALSE !== strpos($orights['divisioncodesvc'],'E')))$urname='Руководитель';
        if(!empty($urname))$orights+=['userrole'=>$urname];
        return $orights;
    }
	/**
	 * Gets string with currently logged in user main role
	 * @return mixed string or boolean FALSE
     */
	public function getActor()
    {
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( $this->useroprights ) {
    		if(	     FALSE !== strpos( $this->useroprights['oprights'],"M" ) ) return 'MASTER';
    		else if( FALSE !== strpos( $this->useroprights['oprights'],"m" ) ) return 'MASTER';
    		else if( FALSE !== strpos( $this->useroprights['oprights'],"F" ) ) return 'EXECUTANT';
    		else if( FALSE !== strpos( $this->useroprights['oprights'],"D" ) ) return 'DISPATCHER';
    		else if( FALSE !== strpos( $this->useroprights['oprights'],"d" ) ) return 'DISPATCHER';
    	}
    	return FALSE;
    }/**
	 * Tests if the currently logged in user have a Dispatcher (CDS) rights
	 * @return boolean result
     */
	public function isUserDispatcher()
    {
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( $this->useroprights ) return (FALSE === strpos($this->useroprights['oprights'],'D') ) ? FALSE : TRUE;
    	return FALSE;
    }
    /**
	 * Tests if the currently logged in user have a Operator ( dispatcher ODS) rights
	 * @return boolean result
     */
	public function isUserOperator()
    {
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( $this->useroprights ) return (FALSE === strpos($this->useroprights['oprights'],'d') ) ? FALSE : TRUE;
    	return FALSE;
    }
    /**
	 * Tests if the currently logged in user have a Master rights
	 * @return boolean result
     */
	public function isUserMaster()	// 180523,vpr,Is the user Master, doesn't matter, ordinary or Head
    {
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( $this->useroprights ) return ((FALSE === strpos($this->useroprights['oprights'],'M') )&&(FALSE === strpos($this->useroprights['oprights'],'m'))) ? FALSE : TRUE;
    	return FALSE;
    }
    public function isUserHMaster()	{ // 180523,vpr, Is user Head-Master?
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( $this->useroprights ) return (FALSE === strpos($this->useroprights['oprights'],'M') ) ? FALSE : TRUE;
    	return FALSE;
    }
    public function isUserOMaster()	{ // 180523,vpr, Is user ordinary (not Head) Master?
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( $this->useroprights ) return (FALSE === strpos($this->useroprights['oprights'],'m') ) ? FALSE : TRUE;
    	return FALSE;
    }
    /**
	 * Tests if the currently logged in user have a foreman rights
	 * $devtype - string, 'L' or 'E', or 'P' - served equipment code
	 * @return boolean result
     */
	public function isUserFitter($devtype=null)
    {
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( !empty($devtype) ) if( FALSE === strpos($this->useroprights['divisioncodesvc'],$devtype))return FALSE;
    	if( $this->useroprights ) return (FALSE === strpos($this->useroprights['oprights'],'F') ) ? FALSE : TRUE;
    	return FALSE;
    }
    /**
	 * Gets error codes (groups) list for logged in user for drop down lists
	 * @return array
     */
	public function getDeviceErrorCodeGroupsList4User()
    {
    	if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
    	if( empty($devtype = substr($this->useroprights['divisioncodesvc'],0,1)) ) return [];

		if( FALSE === ($errcdlist=Yii::$app->db->createCommand('SELECT elerrorcode as errorcode,CONCAT(IFNULL(elerrorcode,"")," ",IFNULL(elerrortext,"")) as errortext FROM elevatorerrorcode where (elerrordevice like "%'.$devtype.'%") and (elerrorcode%100=0 and elerrorcode!=0)')->queryAll()) ) return [];
		$errcdlistmapped = ArrayHelper::map($errcdlist,'errorcode','errortext');
		if( (count($errcdlistmapped) > 1) ||
			((count($errcdlistmapped) == 1)&&( intval($errcdlistmapped['errorcode']) != 0 )) ) return ['0'=>'Все']+$errcdlistmapped;
		
		return [];// No groups were found 
    }
	public static function getDeviceErrorCodesList4User($group=null)
    {
    	if( FALSE === ( $useroprights = self::getUserOpRights())) return [];
    	if( empty($devtype = substr($useroprights['divisioncodesvc'],0,1)) ) return [];

		$sql='SELECT elerrorcode as errorcode,CONCAT(IFNULL(elerrorcode,"")," ",IFNULL(elerrortext,"")) as errortext FROM elevatorerrorcode where (elerrordevice like "%'.$devtype.'%")';
		if(!empty($group)) $sql.=" and (elerrorcode%100!=0 or (elerrorcode=0 and elerrorcode%100=0)) and (cast((elerrorcode-elerrorcode%100)/100 as unsigned)=".intval($group/100).")";
		return (FALSE===$errcdlist) ? [] : 	ArrayHelper::map(Yii::$app->db->createCommand($sql)->queryAll(),'errorcode','errortext');
    }
    /**
	 * Builds the array of employees [[0]=>['id','name'],...] for given division who are the fitters ( occupation_id = 3 )
	 * @param integer $divisionId - division id of currently logged in user, key for the record in division table
	 * @param mixed  $devtype - see description in remarks for getDeviceTypeSqlPredicate()
	 * @return mixed, string if user is logged in and have a rights for some operations, FALSE otherwise
     */
	public function getFittersList($divisionId,$devtype=null)
	{
		$retlist=array();
		if($this->isUserMaster()){
			//----Get all fitters for division
			$fitters = Yii::$app->db
				->createCommand("SELECT id,concat(lastname,' ',COALESCE(firstname,''),' ',COALESCE(patronymic,'')) as name FROM employee where oprights like 'F' and division_id=:id order by name")
				->bindValues([':id' => $divisionId])
				->queryAll();
		}
		else if ( $this->isUserDispatcher() ) {
			//--- Select the divisions, that provides services only for selected device types
			$sql1=Tickets::getDeviceTypeSqlPredicate($devtype);

			//---Get emergency workers 
			$sql="SELECT e.id,concat(divisionname,': ',COALESCE(lastname,''),' ',COALESCE(firstname,''),' ',COALESCE(patronymic,'')) as name FROM employee e join division d on e.division_id=d.id where oprights like 'F' and divisioncode in (".self::LASDIVISION_CODE.','.self::LAS1DIVISION_CODE.')'.Tickets::getDeviceTypeSqlPredicate($devtype)." order by name";
			$fitters = Yii::$app->db->createCommand($sql)->queryAll();
		}
		else return [];
		$retlist = ArrayHelper::map($fitters,'id','name');

		return ['0'=>'-'] + $retlist;
	}
	/**
	 * Prepares the array of pairs [division.id,divisionname] for filling in http select - for divisions where Masters are employed
	 * @param boolean includeLAS - flag to add the [0=>'All'],[8=>'ЛАС'] items to the top of the resulting list
	 *							if null, the [0=>'-'] item will be placed to the top of the resulting list
	 * @param mixed  $devtype - see description in remarks for getDeviceTypeSqlPredicate()
	 * @return array of [id,divisionname]
     */
	public static function getMasterDesksList($includeLAS=FALSE,$devtype=null){
		$retlist=array();
		//$devtypelist=[1=>'L',10=>'E',20=>'S'];	// Service type codes
		$sql="select distinct d.id, d.divisionname from division d left join employee e on e.division_id = d.id where (e.oprights like '%M%')".Tickets::getDeviceTypeSqlPredicate($devtype)." order by divisionname";
		$masterDesks = Yii::$app->db->createCommand($sql)->queryAll();
		$retlist = ArrayHelper::map($masterDesks,'id','divisionname');
		if($includeLAS) $retlist = [ 0=>'Все', self::LASDIVISION_ID=>'ЛАС' ] + $retlist; //--- it's a list for filters, with items"All","EmergencyCrew" 
		else $retlist = [ 0=>'-'] + $retlist;		// it's an ordinary return, but with "nothing is selected" item
		return $retlist;
	}
	/**
	 * Helper function for getMasterDesksList(),getFittersList(). Assemble the string with pridicate for SQL-query 
	 * @param mixed  $devtype - a string(or int) or an array of strings(ints) with service codes that the resulting list of masters will match:
	 *							INT(CHAR):
	 *							----------
	 *							1(L) - elevators
	 *							10(E) - electricical equipment (swithboards)
	 *							20(S) - speakerphones
	 *							if empty, all departments will be placed into resulting list
	 *							!if devtype is string, only the first character is valuable
	 * @param string $searchfieldname - name of DB field for which the predicate will be prepared
	 * @param boolean  $trimAnd - if true, leading AND willbe trimmed
	 * @return string - SQL predicate for query to table 'division'
	 */
	protected static function getDeviceTypeSqlPredicate( $devtype,$searchfieldname='divisioncodesvc',$trimAnd=false ){
		$devtypelist=[1=>'L',10=>'E',20=>'S'];	// Service type codes
		if(is_array($devtype)){
			foreach( $devtype as $dt ){
				unset($dtstr);
				$dtstr = $devtypelist[ intval($dt) ];	// if devtype is int or string representation of int, get it
				if(empty($dtstr))if(is_string($dt))$dtstr = mb_substr($dt,0,1); // character code, L/E/S
				if(empty($dtstr))continue;
				if(FALSE===strpos($sql,"AND"))$sql.=" AND (";
				$sql.=" ($searchfieldname like \"%$dtstr%\") OR";
			}
			if(!empty($sql))$sql=trim($sql,"OR").")";
		}
		else{
			unset($dtstr);
			$dtstr = $devtypelist[ intval($devtype) ];	// if devtype is int or string representation of int, get it
			if(empty($dtstr))if(is_string($devtype))$dtstr = mb_substr($devtype,0,1);	// character code, L/E/S
			if(!empty($dtstr))$sql = " AND ($searchfieldname like \"%$dtstr%\")";
		}
		if($trimAnd)$sql = trim($sql," AND");
		return $sql;
	}
	/**
	 * Prepares the array of pairs [division.id,divisionname] for filling in http select - for divisions where Masters are employed
	 * @return array of [id,oostypetext]
     */
	public static function getOosTypesList($devtype){
		$sql="select id,oostypetext from oostype where ".Tickets::getDeviceTypeSqlPredicate( $devtype,'oostypedevice',true );
		$oosTypes = Yii::$app->db
			->createCommand($sql)->queryAll();
		return ['0'=>'Причина не определена!'] + ArrayHelper::map($oosTypes,'id','oostypetext');
	}
	public static function getHoursList(){
		return ['00'=>'00:00','01'=>'01:00','02'=>'02:00','03'=>'03:00','04'=>'04:00','05'=>'05:00','06'=>'06:00','07'=>'07:00','08'=>'08:00','09'=>'09:00','10'=>'10:00','11'=>'11:00','12'=>'12:00','13'=>'13:00','14'=>'14:00','15'=>'15:00','16'=>'16:00','17'=>'17:00','18'=>'18:00','19'=>'19:00','20'=>'20:00','21'=>'21:00','22'=>'22:00','23'=>'23:00','24'=>'24:00',];
	}
	
	/**
	 * Sets the tilreadflag in ticket log for record with newest time !FOR LOGGED IN USER!
	 * @param integer $id - ticket id
     */
	public static function setReadFlag($id){
		if( FALSE === ( $receiver = Tickets::getUserOpRights() ) ) return;
		//--Here 1 version - set readflag in ticketlog
		$result = Yii::$app->db->createCommand('SELECT tiltime, id FROM ticketlog WHERE tilticket_id = '.$id.' AND tilreceiver_id = '.$receiver['id'].' ORDER BY tiltime DESC LIMIT 1' )->queryOne();		
		Yii::$app->db->createCommand()->update('ticketlog',['tilreadflag'=>'1'],['id'=>$result['id']])->execute();
		//--Here 2 version - set readflag in ticket
		Yii::$app->db->createCommand()->update('ticket',['tiexecutantread'=>'1'],['id'=>$id,'tiexecutant_id'=>$receiver['id']] )->execute();
	}
	/**
	 * Sets the tilreadflag in ticket log for record with newest time !FOR LOGGED IN USER!
	 * @param integer $id - ticket id
	 * @param integer $receiver -  id of person to whom message been sent
     */
	public static function isTicketBeenRead( $id, $receiver ){
		if(!isset($receiver))return FALSE;
		$result = Yii::$app->db->createCommand('SELECT tiltime, tilreadflag FROM ticketlog WHERE tilticket_id = '.$id.' AND tilreceiver_id = '.$receiver.' ORDER BY tiltime DESC LIMIT 1' )->queryOne();		
		//Yii::warning('READ==='.$result,__METHOD__);
		return $result['tilreadflag'] ? TRUE : FALSE;
	}
	
}