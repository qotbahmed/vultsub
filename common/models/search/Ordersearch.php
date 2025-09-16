<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;

/**
 * common\models\search\Ordersearch represents the model behind the search form about `common\models\Orders`.
 */
 class Ordersearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'academy_id', 'user_id', 'created_by', 'updated_by'], 'integer'],
            [['price', 'tax_value', 'total_price', 'amount_paid', 'remaining_amount'], 'number'],
            [['payment_status', 'order_status', 'created_at', 'updated_at', 'notification_preference'], 'safe'],
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
        $query = Orders::find();

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
            'academy_id' => $this->academy_id,
            'user_id' => $this->user_id,
            'price' => $this->price,
            'tax_value' => $this->tax_value,
            'total_price' => $this->total_price,
            'amount_paid' => $this->amount_paid,
            'remaining_amount' => $this->remaining_amount,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'order_status', $this->order_status])
            ->andFilterWhere(['like', 'notification_preference', $this->notification_preference]);

        return $dataProvider;
    }
}
