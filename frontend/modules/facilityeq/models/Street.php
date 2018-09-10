<?php

namespace frontend\modules\facilityeq\models;

use yii\helpers\ArrayHelper;

use frontend\modules\facilityeq\models\District;
use frontend\modules\facilityeq\models\Facility;

use Yii;

/**
 * This is the model class for table "street".
 *
 * @property int $id
 * @property string $streetdistrict
 * @property string $streetname
 * @property string $streetnameru
 * @property string $streetnameeng
 * @property string $streettype
 * @property int $streetcode
 * @property string $streetzip
 * @property int $streetlocality_id
 *
 * @property Facility[] $facilities
 * @property Locality $streetlocality
 */

class Street extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

       
    public static function tableName()
    {
        return 'street';
    }

    /**
     * @inheritdoc
    */

    public function rules()
    {
        return [
            [['streetlocality_id'], 'integer'],
            [['streetlocality_id','streetname','streetnameru'], 'required','message'=>'Заполните поле'],
            [['streetdistrict', 'streetname', 'streetnameru', 'streetnameeng'], 'string', 'max' => 80],
            [['streettype'], 'string', 'max' => 10],
            array(['streetname', 'streetnameru'], 'match', 'pattern' => '/^[0-9А-яА-яЄєІЇіїЁё_\s\']+$/u', 'message' => 'Поле может содержать только буквы кириллицы'),
            array('streetnameeng', 'match', 'pattern' => '/^[0-9A-z\s]+$/', 'message' => 'Поле может содержать только буквы латинского алфавита'),
            [['streetlocality_id'], 'exist', 'skipOnError' => true],
            [['streetdistrict', 'streettype', 'streetnameru'], 'unique', 'targetAttribute' => ['streetdistrict', 'streettype','streetnameru'],'message'=>'Запись с таким именем уже существует.'],
            [['streetdistrict', 'streettype', 'streetname'], 'unique', 'targetAttribute' => ['streetdistrict', 'streettype','streetname'],'message'=>'Запись с таким именем уже существует.'],
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'streetdistrict' => 'Streetdistrict',
            'streetname' => 'Streetname',
            'streetnameru' => 'Streetnameru',
            'streetnameeng' => 'Streetnameeng',
            'streettype' => 'Streettype',
            'streetlocality_id' => 'Streetlocality ID',
        ];
    }


    public function getFacilities()
    {
        return $this->hasOne(Facility::className(), ['fastreet_id' => 'id']);  
    }



    public function getCountfacilities()
    {
        $getcount= $this->getFacilities()->count('id');
        return $getcount;
    }

    public function getMyStreetList($dist = null)
    {
        if ($dist != "") {
            $streetTypes = self::find()->select(['streettype','streetnameru'])->where(['streetdistrict'=> $dist]);
        }
        else {
            $streetTypes = self::find()->select(['streettype','streetnameru']);
        }
        return $streetTypes;
    }      


}
