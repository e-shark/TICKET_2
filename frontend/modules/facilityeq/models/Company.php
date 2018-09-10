<?php

namespace frontend\modules\facilityeq\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $companyname
 * @property string $companyfullname
 * @property string $companynameeng
 * @property string $companycode
 * @property string $companytaxcode
 * @property string $companydate
 * @property string $companyphone
 * @property string $companyfax
 * @property string $companyemail
 * @property string $companyurl
 * @property string $companyzip
 * @property string $companyaddress
 * @property string $companyrole
 * @property string $companydescription
 * @property int $companyform_id
 *
 * @property Companyform $companyform
 * @property Division[] $divisions
 * @property Elevator[] $elevators
 * @property Elevator[] $elevators0
 * @property Elevator[] $elevators1
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companydate'], 'safe'],
            [['companyform_id'], 'integer'],
            [['companyname', 'companyfullname', 'companynameeng'], 'string', 'max' => 100],
            [['companyname', 'companyfullname'], 'required','message'=>'Заполните поле'],
            [['companycode', 'companytaxcode', 'companyzip', 'companyrole'], 'string', 'max' => 10],
            [['companyphone', 'companyfax'], 'string', 'max' => 16],
            [['companyemail', 'companyurl', 'companyaddress', 'companydescription'], 'string', 'max' => 255],
            [['companyform_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companyform::className(), 'targetAttribute' => ['companyform_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' =>  'ID',
            'companyname' => 'Companyname',
            'companyfullname' => 'Companyfullname',
            'companynameeng' => 'Companynameeng',
            'companycode' => 'Companycode',
            'companytaxcode' => 'Companytaxcode',
            'companydate' =>  'Companydate',
            'companyphone' => 'Companyphone',
            'companyfax' =>  'Companyfax',
            'companyemail' => 'Companyemail',
            'companyurl' => 'Companyurl',
            'companyzip' =>  'Companyzip',
            'companyaddress' =>  'Companyaddress',
            'companyrole' => 'Companyrole',
            'companydescription' =>  'Companydescription',
            'companyform_id' =>  'Companyform ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyform()
    {
        return $this->hasOne(Companyform::className(), ['id' => 'companyform_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivisions()
    {
        return $this->hasMany(Division::className(), ['divisioncompany_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElevators()
    {
        return $this->hasMany(Elevator::className(), ['elownercompany_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElevators0()
    {
        return $this->hasMany(Elevator::className(), ['elservicecompany_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElevators1()
    {
        return $this->hasMany(Elevator::className(), ['elsubservicecompany_id' => 'id']);
    }
}
