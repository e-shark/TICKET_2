<?php
namespace frontend\models;
use yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use frontend\models\Report_Titotals;


class Report_Repfailures extends Model
{
	public $datefrom;
	public $dateto;
	public $district;
	public $f_tidevicetype;
	public $calltype;
	public $f_typeoos;
	public $reportpagesize;

	public function generate($params)
	{
        $f1sql = Report_Titotals::fillparamsfiltet1($this,$params);

		$sqltext="SELECT count(tiobjectcode) as cnt, tiobjectcode,tiaddress,tiregion,tiobject_id, division.divisionname, oostype.oostypetext,ticketproblemtype.tiproblemtypetext from ticket left outer join division on division.id=tidivision_id  left join ticketproblemtype on ticketproblemtype.id=ticket.tiproblemtype_id left join oostype on oostype.id=ticket.tioostype_id where 1 $f1sql group by tiobjectcode,tioostype_id  having cnt>1";
		
		$provider = new SqlDataProvider([
			'sql' => $sqltext,
			'pagination'=>['pageSize'=>$this->reportpagesize],
			'sort' => [
				'attributes' => [
					'cnt',
				],
				'defaultOrder' => [ 'cnt' => SORT_DESC ],
			],
		]);
		if( 0 === $this->reportpagesize ) $provider->pagination->pageSize = $provider->totalCount; // Show all records
		return $provider;
	}
}
	