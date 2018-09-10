<?php

namespace frontend\modules\facilityeq\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\facilityeq\models\Company;

/**
 * CompanySearch represents the model behind the search form of `frontend\modules\facilityeq\models\Company`.
 */
class CompanySearch extends Company
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'companyform_id'], 'integer'],
            [['companyname', 'companyfullname', 'companynameeng', 'companycode', 'companytaxcode', 'companydate', 'companyphone', 'companyfax', 'companyemail', 'companyurl', 'companyzip', 'companyaddress', 'companyrole', 'companydescription'], 'safe'],
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
        $query = Company::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ],
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
            'companydate' => $this->companydate,
            'companyform_id' => $this->companyform_id,
        ]);

        $query->andFilterWhere(['like', 'companyname', $this->companyname])
            ->andFilterWhere(['like', 'companyfullname', $this->companyfullname])
            ->andFilterWhere(['like', 'companynameeng', $this->companynameeng])
            ->andFilterWhere(['like', 'companycode', $this->companycode])
            ->andFilterWhere(['like', 'companytaxcode', $this->companytaxcode])
            ->andFilterWhere(['like', 'companyphone', $this->companyphone])
            ->andFilterWhere(['like', 'companyfax', $this->companyfax])
            ->andFilterWhere(['like', 'companyemail', $this->companyemail])
            ->andFilterWhere(['like', 'companyurl', $this->companyurl])
            ->andFilterWhere(['like', 'companyzip', $this->companyzip])
            ->andFilterWhere(['like', 'companyaddress', $this->companyaddress])
            ->andFilterWhere(['like', 'companyrole', $this->companyrole])
            ->andFilterWhere(['like', 'companydescription', $this->companydescription]);

        return $dataProvider;
    }
}