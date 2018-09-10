<?php

namespace  frontend\modules\facilityeq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\District;

/**
 * DistrictSearch represents the model behind the search form of `frontend\models\District`.
 */
class DistrictSearch extends District
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'districtlistno', 'districtlocality_id'], 'integer'],
            [['districtname', 'districtnameeng', 'districtcode'], 'safe'],
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
        $query = District::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'districtlistno' => $this->districtlistno,
            'districtlocality_id' => $this->districtlocality_id,
        ]);

        $query->andFilterWhere(['like', 'districtname', $this->districtname])
            ->andFilterWhere(['like', 'districtnameeng', $this->districtnameeng])
            ->andFilterWhere(['like', 'districtcode', $this->districtcode]);

        return $dataProvider;
    }
}
