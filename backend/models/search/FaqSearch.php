<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Faq;

/**
 * backend\models\search\FaqSearch represents the model behind the search form about `backend\models\Faq`.
 */
 class FaqSearch extends Faq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort', 'created_by', 'updated_by'], 'integer'],
            [['question', 'answer', 'status', 'created_at', 'updated_at'], 'safe'],
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
         $query = Faq::find();

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
             'created_by' => $this->created_by,
             'updated_by' => $this->updated_by,
         ]);

         // Use orderBy to sort the results based on the 'sort' attribute
         $query->orderBy(['sort' => SORT_ASC]);

         $query->andFilterWhere(['like', 'question', $this->question])
             ->andFilterWhere(['like', 'answer', $this->answer])
             ->andFilterWhere(['like', 'status', $this->status])
             ->andFilterWhere(['like', 'category_id', $this->category_id])
             ->andFilterWhere(['like', 'created_at', $this->created_at])
             ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

         return $dataProvider;
     }

}
