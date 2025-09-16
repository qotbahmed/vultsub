<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BehaviorSkillsQuestions;

/**
 * common\models\search\BehaviorSkillsQuestionsSearch represents the model behind the search form about `common\models\BehaviorSkillsQuestions`.
 */
 class BehaviorSkillsQuestionsSearch extends BehaviorSkillsQuestions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'answer', 'created_by', 'updated_by'], 'integer'],
            [['questions', 'note', 'created_at', 'updated_at'], 'safe'],
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
        $query = BehaviorSkillsQuestions::find();

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
            'answer' => $this->answer,
           // 'academy_id' => $this->academy_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'questions', $this->questions])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
