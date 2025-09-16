<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PlayerHealthGoals;

/**
 * common\models\search\PlayerHealthGoalsSearch represents the model behind the search form about `common\models\PlayerHealthGoals`.
 */
 class PlayerHealthGoalsSearch extends PlayerHealthGoals
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'blood_type', 'chronic_diseases', 'allergies_food', 'can_perform_cardio', 'can_perform_strength_training', 'emergency_contact_phone', 'academy_id', 'updated_by', 'created_by'], 'integer'],
            [['height_cm', 'weight_kg', 'body_mass'], 'number'],
            [['allergy_details', 'forbidden_foods', 'forbidden_medications', 'current_medications', 'note', 'created_at', 'updated_at'], 'safe'],
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
        $query = PlayerHealthGoals::find();

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
            'height_cm' => $this->height_cm,
            'weight_kg' => $this->weight_kg,
            'body_mass' => $this->body_mass,
            'blood_type' => $this->blood_type,
            'chronic_diseases' => $this->chronic_diseases,
            'allergies_food' => $this->allergies_food,
            'can_perform_cardio' => $this->can_perform_cardio,
            'can_perform_strength_training' => $this->can_perform_strength_training,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'academy_id' => $this->academy_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'allergy_details', $this->allergy_details])
            ->andFilterWhere(['like', 'forbidden_foods', $this->forbidden_foods])
            ->andFilterWhere(['like', 'forbidden_medications', $this->forbidden_medications])
            ->andFilterWhere(['like', 'current_medications', $this->current_medications])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
