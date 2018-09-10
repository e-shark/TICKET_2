<?php

namespace frontend\modules\facilityeq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\facilityeq\models\Facility;

/**
 * FacilitySearch represents the model behind the search form of `frontend\models\Facility`.
 */
class FacilitySearch extends Facility
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fastoreysnum', 'faporchesnum', 'fastreet_id', 'fadistrict_id'], 'integer'],
            [['facode', 'facodesvc', 'fainventoryno', 'faaddressno', 'fabuildingno', 'fasectionno', 'fabseries', 'fatype', 'fadescription', 'fadate', 'facomdate', 'fadecomdate', 'faserviceno'], 'safe'],
            [['falatitude', 'falongitude'], 'number'],
            [['fadistric', 'fastreetname','fastreettype','elfacility'], 'safe'],
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
        $query = Facility::find()
            ->leftJoin('street', 'street.id = facility.fastreet_id')
            ->leftJoin('elevator', 'elevator.elfacility_id = facility.id ')
            ->with('elevators')
            ->with('fastreet')->distinct()
            ;

        // add conditions that should always apply here

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
                'street.streetname' => [
                    'asc' => ['street.streetname' => SORT_ASC],
                    'desc' => ['street.streetname' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'fabuildingno' => [
                    'asc' => ['fabuildingno' => SORT_ASC],
                    'desc' => ['fabuildingno' => SORT_DESC],
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
        $query->andFilterWhere([
            'id'                    => $this->id,
            'fastoreysnum'          => $this->fastoreysnum,
            'faporchesnum'          => $this->faporchesnum,
            'fadate'                => $this->fadate,
            'facomdate'             => $this->facomdate,
            'fadecomdate'           => $this->fadecomdate,
            'falatitude'            => $this->falatitude,
            'falongitude'           => $this->falongitude,
            'fastreet_id'           => $this->fastreet_id,
            'fadistrict_id'         => $this->fadistrict_id,
            'fabuildingno'          => $this->fabuildingno,
            'elevator.eldevicetype' => $this->elfacility,
            'street.id'             => $this->fastreetname,
        ]);

        $query->andFilterWhere(['like', 'facode', $this->facode])
            ->andFilterWhere(['like', 'facodesvc', $this->facodesvc])
            ->andFilterWhere(['like', 'fainventoryno', $this->fainventoryno])
            ->andFilterWhere(['like', 'faaddressno', $this->faaddressno])
            ->andFilterWhere(['like', 'fasectionno', $this->fasectionno])
            ->andFilterWhere(['like', 'fabseries', $this->fabseries])
            ->andFilterWhere(['like', 'fatype', $this->fatype])
            ->andFilterWhere(['like', 'fadescription', $this->fadescription])
            ->andFilterWhere(['like', 'street.streettype', $this->fastreettype])
            ->andFilterWhere(['like', 'faserviceno', $this->faserviceno]);

        $query->orderBy([
                'street.streetnameru' => SORT_ASC,
                'fabuildingno' => SORT_ASC,
                //'elporchno' => SORT_ASC
        ]);
        
        return $dataProvider;
    }
}
