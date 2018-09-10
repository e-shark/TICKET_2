<?php
namespace frontend\models;
use yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use frontend\models\Tickets;
use frontend\models\Report_Titotals;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Report_Oos extends Model
{
	const NREGION = 8;
	public $datefrom;
	public $dateto;
	public $district;
	public $f_tidevicetype;
	public $calltype;
	public $reportpagesize;
	public $f_tiexecutantdesk;
	public $pivotProvider;
	public $object_id;
	public $sqlView;

	public function generate($params)
	{
        $this::fillparams($params);

		$sqltext="SELECT ticket.*, if(div_id is null, 'Не определен',divisionname) as divisionname,
					 TIMESTAMPDIFF(HOUR,tiincidenttime,now()) as ooshours,
					 oostype.oostypetext,ticketproblemtype.tiproblemtypetext from ticket inner join
					 $this->sqlView r on ticket.id=r.ticket_id left join division  on r.div_id=division.id
					 left join ticketproblemtype on ticketproblemtype.id=ticket.tiproblemtype_id left join
					 oostype on oostype.id=ticket.tioostype_id
				  where r.tiopenedtime between '$this->datefrom' and '$this->dateto'  and".$this->getRegionDivisionsListSql(); 
//Yii::warning('sqltext=' . $sqltext, __METHOD__);        

		//---Prepare the sql statement for tickets according to the user rights
//		$oprights = Tickets::getUserOpRights();
		//if(FALSE !== $oprights )$sqltext = $sqltext.' and tidivision_id = '.$oprights[division_id];

		$this->reportpagesize = 0;
		$provider = new SqlDataProvider([
			'sql' => $sqltext,
			'key' => 'id',
			'pagination'=>['pageSize'=>$this->reportpagesize],
			'sort' => [
				'attributes' => [
					'tiincidenttime',
					'ooshours',
				],
				'defaultOrder' => [ 'tiincidenttime' => SORT_DESC ],
			],
		]);
		if( 0 === $this->reportpagesize ) $provider->pagination->pageSize = $provider->totalCount; // Show all records

		$this->pivotProvider = $this->generatePivotGrid();

		return $provider;
	}

	public function fillparams($params){

		$this->object_id = !empty($params['object_id'] ) ? $params['object_id'] : 1;
		if($this->object_id == 1) $this->sqlView = "report_oos_lift";
		else if ($this->object_id == 2) $this->sqlView = "report_oos_switchboard";
		else return false;//not implemented yet	

		$this->datefrom   = ( !empty($params['datefrom'] ) ) ? $params['datefrom'] :
			("2017-10-04");	// 180418,vpr-replace oldest ticket date on now()
		//$this->datefrom = $params['datefrom'];
  		try{$dateiso=Yii::$app->formatter->asDate($this->datefrom,'yyyy-MM-dd');}catch(\Exception $e){ $dateiso=null;}
		$this->datefrom = $dateiso;
		$filtersql	.=" and (tiopenedtime>'$this->datefrom') ";

		$this->dateto   = empty($params['dateto']) ?  date('d-M-y') : $params['dateto'];
		try{$dateiso=Yii::$app->formatter->asDate($this->dateto,'yyyy-MM-dd');}catch(\Exception $e){ $dateiso=date('-M-y'); }
		$this->dateto = $dateiso;
		if($this->dateto<$this->datefrom)$this->dateto=$this->datefrom;
		$this->dateto.=' 23:59:59';

		$this->f_tiexecutantdesk = 0;
		if( !empty($params['f_tiexecutantdesk'] ) )
			$this->f_tiexecutantdesk = $params['f_tiexecutantdesk'];

		if( !empty($params['district'] ) ) 
			$this->district = $params['district'];
		else  if (empty($this->district)){ 
			$this->district = self::getDistrictsList();
			$this->district = array_values($this->district);
		}
	}

	public function getRegionDivisionsListSql(){
		$is_all_division = $this->f_tiexecutantdesk == 0;
		$districts = $this->district;	
		if(!$is_all_division)
			$divisionlist = "$this->f_tiexecutantdesk";
		$districtF = implode(",",$this->district);
	 	$districtF = str_replace("'","\'",$districtF);
	 	$districtF = str_replace(",","','",$districtF);
		$RegionDivisionsList = "
				(
				(div_id is null and region in ('$districtF')) or";
			for ($i=0; $i<count($districts); $i++) {
		 	$region = $districts[$i];
			if($is_all_division) {
				$divisionlist =self::getDivisionList($this->sqlView,[$region]);
				$divisionlist = array_keys($divisionlist);
				$divisionlist = implode(',', $divisionlist);
			}

		 	$region = str_replace("'","\'",$region);
 			$RegionDivisionsList .=" 
 				(region  = '$region' and  div_id in($divisionlist)) or";
		}
		$RegionDivisionsList = mb_substr($RegionDivisionsList, 0, -2);
		$RegionDivisionsList .=	")
		";
		return $RegionDivisionsList;
	}

	public function generatePivotGrid(){
		if (empty($this->district)) return false;
		$RegionDivisionsListSql = $this->getRegionDivisionsListSql();
		$sql ="select region_id*100 as oid, region,'Всего' as divisionname, 
			count(*) as cnt, count(if( calltype='1562',1,null)) as cnt1562,
			count(if( calltype='Itera2', 1,null)) as cntItera, 
			count(if( TIMESTAMPDIFF(HOUR,plannedtime,now())>0,1,null)) as cntoverdue
			from $this->sqlView where tiopenedtime between '$this->datefrom' and '$this->dateto' and
			$RegionDivisionsListSql
			group by region_id 
			union 
			select  (case when (div_id=8 or div_id=7) then region_id*100+div_id 
					 when (div_id is null) then region_id*100+95
					 else  region_id*100+10+div_id end) as oid, region, 
                (case when (div_id is null) then 'Не определен' else divisionname end) as divisionname,
				count(*) as cnt, count(if( calltype='1562',1,null)) as cnt1562,
				count(if( calltype='Itera2',1,null)) as cntItera,
				count(if( TIMESTAMPDIFF(HOUR,plannedtime,now())>0,1,null)) as cntoverdue
				from $this->sqlView  left join division on id=div_id where 
				tiopenedtime between '$this->datefrom' and '$this->dateto' and $RegionDivisionsListSql 
				group by region_id,div_id
	    	union
	        select 100000 as oid, 'Итого' as region,'Все' as divisionname, 
			count(*) as cnt, count(if( calltype='1562',1,null)) as cnt1562,
			count(if( calltype='Itera2', 1,null)) as cntItera, 
			count(if( TIMESTAMPDIFF(HOUR,plannedtime,now())>0,1,null)) as cntoverdue
			from $this->sqlView where tiopenedtime between '$this->datefrom' and '$this->dateto'
			and $RegionDivisionsListSql
 			 order by oid ";
//Yii::warning('sqltext=' . $sql, __METHOD__); 

//		$provider->pagination->pageSize = $provider->totalCount;
		return new SqlDataProvider(['sql' => $sql,'pagination'=>false]);
}

	public static function getDistrictsList(){
    	$districts = ArrayHelper::map(Yii::$app->db->createCommand('SELECT id,districtname FROM district where districtlocality_id=159')->queryAll(),'districtname','districtname');
    	return $districts;
    }
    
    public static function getDivisionList($sqlView,$Districts = [])
	{
		$sql = "SELECT distinct div_id, (select divisionname from division where id =div_id) as divisionname
 			from $sqlView where div_id is not null ";
		if(!empty($Districts)){
		 	$Districts = implode(',', $Districts);
		 	$Districts = str_replace("'","\'",$Districts);
		 	$Districts = str_replace(",","','",$Districts);
		   
		    $sql .= "and region in  ('$Districts') ";
		}
		$sql .= "order by (case when (div_id=8 or div_id=7) then  div_id else 100+div_id end);";

		$divisions = Yii::$app->db->createCommand($sql)->queryAll();
		$retlist = ArrayHelper::map($divisions,'div_id','divisionname');
		$retlist = [ 0=>'Все'] + $retlist;
	
		return $retlist;
	}

    public static function getDivisionListHTML($sqlView, $includeLAS =TRUE, $Districts)
	{
		$retlist = self::getDivisionList($sqlView, $Districts);
		$res = 'Подр.исполнителя:&nbsp'.Html::dropDownList('f_tiexecutantdesk', $retlist[0],  $retlist,['class'=>'form-control']);
		return $res;
	}

}
