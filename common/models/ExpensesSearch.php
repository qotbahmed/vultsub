<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Expenses;

/**
 * common\models\ExpensesSearch represents the model behind the search form about `common\models\Expenses`.
 */
class ExpensesSearch extends Expenses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'expenses_category_id','main_category_id', 'academy_id', 'academy_sport_id', 'created_by', 'updated_by'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at'], 'safe'],
            [['created_at', 'updated_at'], 'default', 'value' => null],

            [['amount'], 'number'],
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
        $query = Expenses::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->created_at !== null) {
            $startDate = date('Y-m-d 00:00:00', strtotime($this->created_at));
            $endDate = date('Y-m-d 23:59:59', strtotime($this->created_at));

            $query->andFilterWhere(['between', 'created_at', $startDate, $endDate]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'expenses_category_id' => $this->expenses_category_id,
            'academy_id' => $this->academy_id,
            'academy_sport_id' => $this->academy_sport_id,
            'main_category_id' => $this->main_category_id,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
