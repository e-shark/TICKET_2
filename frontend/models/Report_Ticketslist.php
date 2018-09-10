<?php
namespace frontend\models;
use yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use frontend\models\Tickets;
use frontend\models\Report_Titotals;


class Report_Ticketslist extends Model
{
	public $datefrom;
	public $dateto;
	public $district;
	public $calltype;
    public $status;
    public $statusremote;
    public $tifindstr;
    public $f_tidevicetype;
    public $f_tiexecutantdesk;
    public $f_tiexecutant;
    public $tiobjectcode;
    public $f_statusoos;
    public $f_typeoos;
    public $reportpagesize;
    
    //public $sqls;
    //public $params;

    public function rules()
    {
        return [
			//[['dateto','datefrom'],'required'],
            [['dateto','datefrom'],'date','format'=>'php: d-m-Y'],
        ];
    }
       public function attributeLabels()
    {
        return [
            'district'=>'Район: ',
			'calltype'=>'Источник: ',
            'datefrom'=>'Дата от: ',
            'dateto'=>'Дата по: ',
        ];
    }
	public function generate($params)
	{	
        $f1sql = Report_Titotals::fillparamsfiltet1($this,$params);
        
        //--- to get column alias worked in where clause, execute select * from (select ...)    
        $sqltext='SELECT * from (SELECT ticket.*, oostype.oostypetext,ticketproblemtype.tiproblemtypetext, CONCAT(lastname," ", coalesce(firstname,"")) as executant,division.id as executantdeskid FROM ticket left join employee on employee.id=tiexecutant_id left join division on division.id=employee.division_id left join ticketproblemtype on ticketproblemtype.id=ticket.tiproblemtype_id left join oostype on oostype.id=ticket.tioostype_id) t';
        if( FALSE === stristr($f1sql,"where" ) ) $f1sql = ' where '.ltrim($f1sql," and");
        $sqltext .= $f1sql;

        //$this->sqls=$sqltext;
        //$this->params=$params;

        $provider = new SqlDataProvider([
            'sql' => $sqltext,
            'key' => 'id',
            'pagination'=>['pageSize'=>$this->reportpagesize],
            'sort' => [
                'attributes' => [
                    'tipriority',
                    'ticode',
                    'tistatus',
                    'tistatustime',
                    'tiplannedtimenew',
                    'tiaddress'=>[
                        'asc'=>['tiregion'=>SORT_ASC,'tiaddress'=>SORT_ASC],
                        'desc'=>['tiregion'=>SORT_DESC,'tiaddress'=>SORT_DESC],
                    ],
                    'executant'
                ],
                'defaultOrder' => [ 'ticode' => SORT_DESC ],
            ],
        ]);
        if( 0 === $this->reportpagesize ) $provider->pagination->pageSize = $provider->totalCount; // Show all records
        return $provider;
    }
    public function isUserFitter(){return false;}
}