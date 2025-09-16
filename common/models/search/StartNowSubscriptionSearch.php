<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StartNowSubscription;

/**
 * common\models\search\StartNowSubscriptionSearch represents the model behind the search form about `common\models\StartNowSubscription`.
 */
 class StartNowSubscriptionSearch extends StartNowSubscription
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'branches_count'], 'integer'],
            [['created_at', 'status', 'name', 'phone', 'academy_name', 'preferred_time'], 'safe'],
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
        $query = StartNowSubscription::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'branches_count' => $this->branches_count,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'academy_name', $this->academy_name])
            ->andFilterWhere(['like', 'preferred_time', $this->preferred_time]);

        return $dataProvider;
    }
}
