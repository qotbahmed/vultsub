<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AcademyBehaviorSkillsQuestions;

/**
 * common\models\search\AcademyBehaviorSkillsQuestionsSearch represents the model behind the search form about `common\models\AcademyBehaviorSkillsQuestions`.
 */
 class AcademyBehaviorSkillsQuestionsSearch extends AcademyBehaviorSkillsQuestions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'behavior_id', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
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
        $query = AcademyBehaviorSkillsQuestions::find();

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
            'behavior_id' => $this->behavior_id,
            'academy_id' => $this->academy_id,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
