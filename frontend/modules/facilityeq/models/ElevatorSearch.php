<?php

namespace  frontend\modules\facilityeq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

use  frontend\modules\facilityeq\models\Elevator;
use  frontend\modules\facilityeq\models\Facility;
use  frontend\modules\facilityeq\models\Street;
use  frontend\modules\facilityeq\models\District;
use  frontend\modules\facilityeq\models\Rtu;

/**
 * ElevatorSearch represents the model behind the search form of `frontend\models\Elevator`.
 */
class ElevatorSearch extends Elevator
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'elremoteid', 'eldevicetype', 'elload', 'elstops', 'elporchno', 'elrtu_id', 'elfacility_id', 'eldivision_id', 'elperson_id'], 'integer'],
            [['elserialno', 'elmodel', 'eldate', 'eldoortype', 'eltype', 'elporchpos', 'elinventoryno', 'elregyear'], 'safe'],
            [['elspeed'], 'number'],
            [['elregion','eldistrict','elstreettype','elstreetname'],'safe']
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Elevator::find()
            ->leftJoin('facility', 'facility.id = elevator.elfacility_id')
            ->leftJoin('street',   'facility.fastreet_id = street.id')
            ->leftJoin('district', 'facility.fadistrict_id = district.id')
            ->leftJoin('rtu', 'rtu.rtufacility_id = facility.id')->distinct()
            //->orderby('street.streetnameru')
 //       ->joinWith(['elfacility'])
 //       ->joinWith(['elregion'])
 //       ->joinWith(['elrtu']);
            ;



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ],
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                'street.streetnameru' => [
                    'asc' => ['street.streetnameru' => SORT_ASC],
                    'desc' => ['street.streetnameru' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'facility.fabuildingno' => [
                    'asc' => ['facility.fabuildingno' => SORT_ASC],
                    'desc' => ['facility.fabuildingno' => SORT_DESC],
                    'default' => SORT_ASC,
                ],    
                'elporchno' => [
                    'asc' => ['elporchno' => SORT_ASC],
                    'desc' => ['elporchno' => SORT_DESC],
                    'default' => SORT_ASC,
                ],     
            ]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        //'ElevatorSearch[eldevicetype]'  => $par1, 1,10
        //'ElevatorSearch[eldistrict]'    => $par2, 
        //'ElevatorSearch[elstreettype]'  => $par3,
        //'ElevatorSearch[elstreetname]'  => $par4,
        //'ElevatorSearch[elfacility_id]' => $par5 ]);

        $query->andFilterWhere([
            'id'                        => $this->id,
            'elremoteid'                => $this->elremoteid,
            'eldate'                    => $this->eldate,
            'elload'                    => $this->elload,
            'elspeed'                   => $this->elspeed,
            'elstops'                   => $this->elstops,
            'elporchno'                 => $this->elporchno,
            'elrtu_id'                  => $this->elrtu_id,
            'eldivision_id'             => $this->eldivision_id,
            'elperson_id'               => $this->elperson_id,
            'elfacility_id'             => $this->elfacility_id,
            'street.id'                 => $this->elstreetname,
            'facility.fadistrict_id'    => $this->eldistrict,
            'eldevicetype'              => $this->eldevicetype,
            'facility.id'               => $this->elfacility_id,
        ]);

        $query->andFilterWhere(['like', 'elevator.elserialno', $this->elserialno])
            ->andFilterWhere(['like', 'elevator.elmodel', $this->elmodel])
            ->andFilterWhere(['like', 'elevator.eldoortype', $this->eldoortype])
            ->andFilterWhere(['like', 'elevator.eltype', $this->eltype])
            ->andFilterWhere(['like', 'elevator.elporchpos', $this->elporchpos])
            ->andFilterWhere(['like', 'street.streettype', $this->elstreettype])
            ->andFilterWhere(['like', 'elevator.elinventoryno', $this->elinventoryno])
            ->andFilterWhere(['like', 'elevator.elregyear', $this->elregyear]);

        $query->orderBy([
                'street.streetnameru' => SORT_ASC,
                'facility.fabuildingno' => SORT_ASC,
                'elporchno' => SORT_ASC
        ]);


        return $dataProvider;
    }
}
