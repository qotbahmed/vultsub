<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MedicalQuestions;

/**
 * common\models\search\MedicalQuestionsSearch represents the model behind the search form about `common\models\MedicalQuestions`.
 */
 class MedicalQuestionsSearch extends MedicalQuestions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'updated_by', 'created_by', 'question_type'], 'integer'],
            [['created_at', 'updated_at', 'questions', 'options'], 'safe'],
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
        $query = MedicalQuestions::find();

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
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
            'question_type' => $this->question_type,
        ]);

        $query->andFilterWhere(['like', 'questions', $this->questions])
            ->andFilterWhere(['like', 'options', $this->options]);

        return $dataProvider;
    }
}
