<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transactions;
use yii\db\Expression;

/**
 * TransactionsSearch represents the model behind the search form for `common\models\Transactions`.
 */
class TransactionsSearch extends Transactions
{
    /**
     * Additional attribute to filter the receipt number order.
     * Expected values: 'asc' for ascending; 'desc' for descending.
     *
     * @var string
     */
    public $order_receipt;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // The following fields are integer or number types
            [['id', 'academy_id', 'financial_balance_id', 'created_by', 'updated_by', 'source_id'], 'integer'],
            [['amount'], 'number'],
            // Those attributes are considered safe (searchable)
            [['created_at', 'updated_at', 'type', 'source', 'payment_method', 'type_of_safe', 'note', 'receipt_number', 'order_receipt'], 'safe'],
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Transactions::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Load filter parameters
        $this->load($params);

        if (!$this->validate()) {
            // Return unfiltered data if validation fails
            return $dataProvider;
        }

        // Apply filter conditions on basic attributes.
        $query->andFilterWhere([
            'id' => $this->id,
            'academy_id' => $this->academy_id,
            'financial_balance_id' => $this->financial_balance_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'source_id' => $this->source_id,
            'amount' => $this->amount,
        ]);

        // For fields that may be searched with 'like'
        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'type_of_safe', $this->type_of_safe])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'receipt_number', $this->receipt_number]);

        // Apply ordering filter for receipt_number based on its numeric part.
        if (!empty($this->order_receipt)) {
            $direction = ($this->order_receipt === 'asc') ? 'ASC' : 'DESC';
            // The following expression extracts the number starting from the 4th character,
            // casts it to an unsigned integer, and sorts accordingly.
            $query->orderBy(new Expression("CAST(SUBSTR(receipt_number, 4) AS UNSIGNED) $direction"));
        }

        return $dataProvider;
    }
}
