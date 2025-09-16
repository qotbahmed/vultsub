<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SubscriptionDiscounts;

/**
 * common\models\search\SubscriptionDiscountsSearch represents the model behind the search form about `common\models\SubscriptionDiscounts`.
 */
 class SubscriptionDiscountsSearch extends SubscriptionDiscounts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'promos_id', 'subscription_id', 'created_by', 'updated_by', 'amount', 'percentage'], 'integer'],
            [['created_at', 'updated_at', 'name', 'discount_type', 'allow_stack'], 'safe'],
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
        $query = SubscriptionDiscounts::find();

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
            'promos_id' => $this->promos_id,
            'subscription_id' => $this->subscription_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'amount' => $this->amount,
            'percentage' => $this->percentage,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'discount_type', $this->discount_type])
            ->andFilterWhere(['like', 'allow_stack', $this->allow_stack]);

        return $dataProvider;
    }
}
