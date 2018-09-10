<?php

namespace frontend\modules\facilityeq\models;

use Yii;

/**
 * This is the model class for table "rtu".
 *
 * @property int $id
 * @property int $rtusysno
 * @property int $rtucomno
 * @property string $rtuimei
 * @property string $rtuserialno
 * @property string $rtumodel
 * @property string $rtuphone
 * @property string $rtuip
 * @property string $clocksyncperm
 * @property string $rtucode
 * @property int $rtuporchno
 * @property string $rtudescr
 * @property int $rtufacility_id
 *
 * @property Elevator[] $elevators
 * @property Facility $rtufacility
 */
class Rtu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */



    public static function tableName()
    {
        return 'rtu';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
/*    public static function getDb()
    {
        return Yii::$app->get('db1');
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rtusysno', 'rtucomno', 'rtuporchno', 'rtufacility_id'], 'integer'],
            [['rtuimei', 'rtuphone', 'rtuip'], 'string', 'max' => 20],
            [['rtuserialno'], 'string', 'max' => 50],
            [['rtumodel', 'rtudescr'], 'string', 'max' => 100],
            [['clocksyncperm'], 'string', 'max' => 1],
            [['rtucode'], 'string', 'max' => 10],
            [['rtufacility_id'], 'exist', 'skipOnError' => true, 'targetClass' => Facility::className(), 'targetAttribute' => ['rtufacility_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rtusysno' => 'Rtusysno',
            'rtucomno' => 'Rtucomno',
            'rtuimei' => 'Rtuimei',
            'rtuserialno' => 'Rtuserialno',
            'rtumodel' => 'Rtumodel',
            'rtuphone' => 'Rtuphone',
            'rtuip' => 'Rtuip',
            'clocksyncperm' => 'Clocksyncperm',
            'rtucode' => 'Rtucode',
            'rtuporchno' => 'Rtuporchno',
            'rtudescr' => 'Rtudescr',
            'rtufacility_id' => 'Rtufacility ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElevators()
    {
        return $this->hasMany(Elevator::className(), ['elrtu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRtufacility()
    {
        return $this->hasOne(Facility::className(), ['id' => 'rtufacility_id']);
    }
}
