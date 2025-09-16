<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AcademyRequestSearch represents the model behind the search form of `common\models\AcademyRequest`.
 */
class AcademyRequestSearch extends AcademyRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'branches_count', 'requested_at', 'approved_at', 'rejected_at', 'created_by', 'updated_by', 'portal_academy_id', 'portal_user_id', 'user_id'], 'integer'],
            [['academy_name', 'manager_name', 'email', 'phone', 'address', 'city', 'sports', 'description', 'status', 'notes'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = AcademyRequest::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'requested_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
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
            'branches_count' => $this->branches_count,
            'requested_at' => $this->requested_at,
            'approved_at' => $this->approved_at,
            'rejected_at' => $this->rejected_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'portal_academy_id' => $this->portal_academy_id,
            'portal_user_id' => $this->portal_user_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'academy_name', $this->academy_name])
            ->andFilterWhere(['like', 'manager_name', $this->manager_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'sports', $this->sports])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
