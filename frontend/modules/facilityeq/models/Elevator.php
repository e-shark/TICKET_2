<?php

namespace  frontend\modules\facilityeq\models;

use  frontend\modules\facilityeq\models\District;
use  frontend\modules\facilityeq\models\Rtu;
use  frontend\modules\facilityeq\models\Street;
use  frontend\modules\facilityeq\models\Facility;
use  frontend\modules\facilityeq\models\Company;
use  frontend\modules\employeeeq\models\Division;
use  frontend\modules\employeeeq\models\Employee;
use  yii\helpers\ArrayHelper;


use Yii;

/**
 * This is the model class for table "elevator".
 *
 * @property int $id
 * @property int $elremoteid
 * @property int $eldevicetype
 * @property string $elserialno
 * @property string $elmodel
 * @property string $eldate
 * @property int $elload
 * @property string $elspeed
 * @property int $elstops
 * @property string $eldoortype
 * @property string $eltype
 * @property int $elporchno
 * @property string $elporchpos
 * @property string $elinventoryno
 * @property string $elregyear
 * @property int $elrtu_id
 * @property int $elfacility_id
 * @property int $eldivision_id
 * @property int $elperson_id
 *
 * @property Rtu $elrtu
 * @property Facility $elfacility
 * @property Division $eldivision
 * @property Employee $elperson
 */
class Elevator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $myattr;
    public $selecteelreg;
    public $elregion;
    public $eldistrict;
    public $elstreetname;
    public $elstreettype;

/*    public static function getDb()
    {
        return Yii::$app->get('db1');
    }*/

    public static function tableName()
    {
        return 'elevator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['elremoteid', 'eldevicetype', 'elload', 'elstops', 'elporchno', 'elfacility_id'], 'integer'],
            [['eldate','myattr','elregion','eldistrict','elstreetname','elstreettype','elrtu_id','elremoteid',
                'eldivision_id','elperson_id','elownercompany_id','elservicecompany_id','elsubservicecompany_id' ], 'safe'],
            [['elspeed'], 'number'],
            [['elfacility_id', 'elinventoryno','elstreetname',], 'required','message'=>'Заполните поле'],
            [['elserialno', 'elmodel', 'elinventoryno'], 'string', 'max' => 50],
            [['eldoortype', 'eltype', 'elporchpos'], 'string', 'max' => 20],
            [['elregyear'], 'string', 'max' => 4],
            //[['elrtu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rtu::className(), 'targetAttribute' => ['elrtu_id' => 'id']],
            [['elfacility_id'], 'exist', 'message'=>'Заполните поле'],
            [['elinventoryno'], 'exist', 'skipOnError' => true, 'targetClass' => Facility::className(), 'targetAttribute' => ['elfacility_id' => 'id']],
            
            //[['eldivision_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['eldivision_id' => 'id']],
            //[['elperson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['elperson_id' => 'id']],
        ];
    }
           
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'elremoteid' => 'Elremoteid',
            'eldevicetype' => 'Eldevicetype',
            'elserialno' => 'Elserialno',
            'elmodel' => 'Elmodel',
            'eldate' => 'Eldate',
            'elload' => 'Elload',
            'elspeed' => 'Elspeed',
            'elstops' => 'Elstops',
            'myattr' => 'Myattr',
            'eldistrict' => 'Eldistrict',
            'elstreetname' => 'Elstreetname',
            'elstreettype' => 'Elstreettype',
            'eldoortype' => 'Eldoortype',
            'eltype' => 'Eltype',
            'elporchno' => 'Elporchno',
            'elporchpos' => 'Elporchpos',
            'elinventoryno' => 'Elinventoryno',
            'elregyear' => 'Elregyear',
            'elrtu_id' => 'Elrtu ID',
            'elfacility_id' => 'Elfacility ID',
            'eldivision_id' => 'Eldivision ID',
            'elperson_id' => 'Elperson ID',
            'elownercompany_id' => 'Elownercompany ID',
            'elservicecompany_id' => 'Elservicecompany ID',
            'elsubservicecompany_id' => 'Elsubservicecompany ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElrtu()
    {
        return $this->hasOne(Rtu::className(), ['id' => 'elrtu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElfacility()
    {
        return $this->hasOne(Facility::className(), ['id' => 'elfacility_id']);
    }

    public function getElregion()
    {
        return $this->hasOne(District::className(), ['districtlocality_id'=>159])
            ->viaTable('facility', ['fadistrict_id' => 'id']);
    }

    public function getElstreet()
    {
        return $this->hasOne(Street::className(), ['id' => 'elfacility_id'])
            ->viaTable('facility', ['elfacility_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEldivision()
    {
        return $this->hasOne(Division::className(), ['id' => 'eldivision_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElperson()
    {
        return $this->hasOne(Employee::className(), ['id' => 'elperson_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElownercompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'elownercompany_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElservicecompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'elservicecompany_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElsubservicecompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'elsubservicecompany_id']);
    }



    public function getRtuval()
    {
                
        $rval  = $this->elrtu->rtumodel != null ? 'Модель: '          . $this->elrtu->rtumodel . nl2br("\n") : '' ;
        $rval .= $this->elrtu->rtuphone != null ? 'Телефон: '        . $this->elrtu->rtuphone . nl2br("\n") : '' ;
        $rval .= $this->elrtu->rtuserialno != null ? 'Серийный №: '  . $this->elrtu->rtuserialno . nl2br("\n") : '' ; 

        return $rval;
    }

    public function getRegionName()
    {

        $addr = mb_convert_case($this->elfacility->fadistrict->districtname, MB_CASE_TITLE, "UTF-8"). " р-н " ;
        return $addr;
    }

    public function getRegionid()
    {

        $addr = $this->elfacility->fadistrict->fadistrict_id ;
        return $addr;
    }

    public function getMyAddrName()
    {

        //$addr = mb_convert_case($this->elfacility->fastreet->streetdistrict, MB_CASE_TITLE, "UTF-8"). " р-н, " .
        $addr = $this->elfacility->fastreet->streettype .' '.  $this->elfacility->fastreet->streetnameru ;
        return $addr;
    }

    public function getMyDeviceTypeName()
    {
        $devtype= $this->eldevicetype;
        if (isset($devtype))
        {
            if ($devtype==1) {$devtypename = "Лифт";}
            if ($devtype==10) {$devtypename = "ЭЩ";}
            if ($devtype==20) {$devtypename = "Домофон";}
        }else 
        {
            $devtypename = "Не задан";
        }
        return $devtypename;
    }

    public function getMyBuildingName()
    {
        $addr =$this->elfacility->fabuildingno;
        return $addr;
    }
        //if(eldevicetype=1, (select CAST(sum(eldevicetype)/eldevicetype as UNSIGNED) from elevator where eldevicetype=1  and elfacility_id =7457),0) as countEL,
        //if(eldevicetype=10,(select CAST(sum(eldevicetype)/eldevicetype as UNSIGNED) from elevator where eldevicetype=10 and elfacility_id =7457),0) as countSB,
        //if(eldevicetype=20,(select CAST(sum(eldevicetype)/eldevicetype as UNSIGNED) from elevator where eldevicetype=20 and elfacility_id =7457),0) as  countD

    public function getCountAll()
    {
        $countAll = self::find()
            ->select(['id',
                'if(eldevicetype=1, (select CAST(sum(eldevicetype)/eldevicetype as UNSIGNED) from elevator where eldevicetype=1  and elfacility_id =' . $this->elfacility_id . '),0) as countEL',
                'if(eldevicetype=10,(select CAST(sum(eldevicetype)/eldevicetype as UNSIGNED) from elevator where eldevicetype=10 and elfacility_id =' . $this->elfacility_id . '),0) as countSW',
                'if(eldevicetype=20,(select CAST(sum(eldevicetype)/eldevicetype as UNSIGNED) from elevator where eldevicetype=20 and elfacility_id =' . $this->elfacility_id . '),0) as  countBUZ'
                ])
            ->andwhere(['elfacility_id'=>$this->elfacility_id])
            ->andwhere(['id'=>$this->id])
            ->asArray()->all();
        return $countAll;
    } 
    public function getVal($val1,$val2)
    {

        $val1 = $val2;
        return $val1;
    }


    
    public function getCallTypesList($streetname = null)
    {
        if ($streetname!=""){
            $streetTypes = Elevator::find()->select(['street.streettype','street.id','street.streetnameru','facility.id as fid','facility.fabuildingno as fbld'])
                ->leftJoin('facility', 'facility.id = elevator.elfacility_id')
                ->leftJoin('street',   'facility.fastreet_id = street.id')
                ->where(['facility.fastreet_id'=>$streetname])
                ->orderBy('facility.fabuildingno ASC')->distinct();
        }
        else
        {
            $streetTypes = Elevator::find()->select(['street.streettype','street.id','street.streetnameru','facility.id as fid','facility.fabuildingno as fbld'])
                ->leftJoin('facility', 'facility.id = elevator.elfacility_id')
                ->leftJoin('street',   'facility.fastreet_id = street.id')
                ->orderBy('facility.fabuildingno ASC')->distinct();
        }
        return $streetTypes;
    }

    public function getMyStreetList($dist = null)
    {
        if ($dist!=""){
           // $distrname = District::find()->select('districtname')->where(['id'=>$dist]);
            $streetTypes = Elevator::find()->select(['street.streettype','street.id','street.streetnameru','facility.fabuildingno'])
                ->leftJoin('facility', 'facility.id = elevator.elfacility_id')
                ->leftJoin('street',   'facility.fastreet_id = street.id')
                //->where(['street.streetdistrict'=>$distrname])
                ->where(['facility.fadistrict_id'=>$dist])
                ;
                
        } else {
            $streetTypes = Elevator::find()->select(['street.streettype','street.id','street.streetnameru','facility.fabuildingno'])
                ->leftJoin('facility', 'facility.id = elevator.elfacility_id')
                ->leftJoin('street',   'facility.fastreet_id = street.id')                
                ;
        }

        return $streetTypes;
    }

    //!!!!!!!!!!
    public function getStreetList($dist = null)
    {
            $distrname = District::find()->select('id')->where(['districtname'=>$dist]);
            $streetList = $this->getMyStreetList($distrname);
            $List =  ArrayHelper::map(
                $streetList 
                ->select(['street.id','street.streettype', 'street.streetnameru'])
                ->orderBy('street.streetnameru')->distinct()
                ->asArray()->all(),
                // 'id', 'streetnameru' ); 
                'id', 
                function($model) {
                    return $model['streettype'].' '.$model['streetnameru'];
                });
            return $List;
    }
    
    public function getBuildList($street = null)
    {
        $buildList = $this->getMyStreetList("");
        //$str = "вул. Академика Павлова";
            $List =  ArrayHelper::map(
                $buildList 
                ->select(['facility.id as id','facility.fabuildingno as buildingno'])
                //->where(['concat(street.streettype," ",street.streetnameru)'=>$street])
                ->where(['street.id'=>$street])
                ->orderBy('facility.fabuildingno ASC')->distinct()
                ->asArray()->all(),
                // 'id', 'streetnameru' ); 
                'id', 
                'buildingno');
        return $List;
    }
    //!!!!!!!!!!!!

    public function getStrid($elstreettype = null, $elstreetnameru = null)
    {
        $streetID = Street::find()->select('id')->where(['streettype'=>$elstreettype])->andwhere(['streetnameru' =>$elstreetnameru])->all();
        return $streetID;
    }




    public $useroprights;   // the 1-dimention array  ['id','division_id','oprights',] with currently logged in user rights

     public static function getUserOpRights()
    {
        if(Yii::$app->user->isGuest) return FALSE;  // user is not currently logged in

        return Yii::$app->db    // may be FALSE, if user have not a corresponding record in employee table
            ->createCommand('SELECT e.id,e.division_id,e.oprights,d.divisioncodesvc from employee e left join division d on d.id=e.division_id where user_id=:uid')->bindValues([':uid'=>Yii::$app->user->id])
            ->queryOne();
    }
    /**
     * Gets string with currently logged in user main role
     * @return mixed string or boolean FALSE
     */
    public function getActor()
    {
        if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
        if( $this->useroprights ) {
            if(      FALSE !== strpos( $this->useroprights['oprights'],"M" ) ) return 'MASTER';
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
    public function isUserMaster()  // 180523,vpr,Is the user Master, doesn't matter, ordinary or Head
    {
        if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
        if( $this->useroprights ) return ((FALSE === strpos($this->useroprights['oprights'],'M') )&&(FALSE === strpos($this->useroprights['oprights'],'m'))) ? FALSE : TRUE;
        return FALSE;
    }
    public function isUserHMaster() { // 180523,vpr, Is user Head-Master?
        if( !isset($this->useroprights) ) $this->useroprights = $this->getUserOpRights();
        if( $this->useroprights ) return (FALSE === strpos($this->useroprights['oprights'],'M') ) ? FALSE : TRUE;
        return FALSE;
    }
    public function isUserOMaster() { // 180523,vpr, Is user ordinary (not Head) Master?
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


}

