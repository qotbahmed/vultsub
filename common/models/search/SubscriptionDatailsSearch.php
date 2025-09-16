<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SubscriptionDetails;

/**
 * common\models\search\SubscriptionDatailsSearch represents the model behind the search form about `common\models\SubscriptionDetails`.
 */
 class SubscriptionDatailsSearch extends SubscriptionDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'player_id', 'packages_id', 'sports_id', 'subscription_id', 'created_by', 'updated_by', 'classes', 'amount'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at', 'package_name', 'sport_name'], 'safe'],
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
        $query = SubscriptionDetails::find();

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
            'player_id' => $this->player_id,
            'packages_id' => $this->packages_id,
            'sports_id' => $this->sports_id,
            'subscription_id' => $this->subscription_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'classes' => $this->classes,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'package_name', $this->package_name])
            ->andFilterWhere(['like', 'sport_name', $this->sport_name]);

        return $dataProvider;
    }
}
