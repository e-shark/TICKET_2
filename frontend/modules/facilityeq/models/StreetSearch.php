<?php

namespace frontend\modules\facilityeq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\facilityeq\models\Street;

/**
 * StreetSearch represents the model behind the search form of `frontend\models\Street`.
 */
class StreetSearch extends Street
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'streetcode', 'streetlocality_id'], 'integer'],
            [['streetdistrict', 'streetname', 'streetnameru', 'streetnameeng', 'streettype', 'streetzip'], 'safe'],
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
        $query = Street::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ],
            'sort' => [
                'defaultOrder' => [
                    'streetnameru' => SORT_ASC
                ]
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
            'id' => $this->id,
            'streetcode' => $this->streetcode,
            'streetlocality_id' => $this->streetlocality_id,
        ])->orderBy('streetnameru');

        $query->andFilterWhere(['like', 'streetdistrict', $this->streetdistrict])
            ->andFilterWhere(['like', 'streetname', $this->streetname])
            ->andFilterWhere(['like', 'streetnameru', $this->streetnameru])
            ->andFilterWhere(['like', 'streetnameeng', $this->streetnameeng])
            ->andFilterWhere(['like', 'streettype', $this->streettype])
            ->andFilterWhere(['like', 'streetzip', $this->streetzip]);//->orderBy('streetnameru');

        return $dataProvider;
    }


}
