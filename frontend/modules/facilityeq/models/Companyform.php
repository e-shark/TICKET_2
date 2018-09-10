<?php

namespace frontend\modules\facilityeq\models;

use Yii;

/**
 * This is the model class for table "companyform".
 *
 * @property int $id
 * @property string $companyform
 * @property string $companyformeng
 * @property string $companyformname
 * @property string $companyformnameeng
 * @property int $companyformcode
 * @property int $companyformlistno
 *
 * @property Company[] $companies
 */
class Companyform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companyform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyformcode', 'companyformlistno'], 'integer'],
            [['companyform', 'companyformeng'], 'string', 'max' => 20],
            [['companyformname', 'companyformnameeng'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'companyform' => Yii::t('app', 'Companyform'),
            'companyformeng' => Yii::t('app', 'Companyformeng'),
            'companyformname' => Yii::t('app', 'Companyformname'),
            'companyformnameeng' => Yii::t('app', 'Companyformnameeng'),
            'companyformcode' => Yii::t('app', 'Companyformcode'),
            'companyformlistno' => Yii::t('app', 'Companyformlistno'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['companyform_id' => 'id']);
    }
}
