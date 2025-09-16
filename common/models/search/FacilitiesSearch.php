<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Facilities;

/**
 * common\models\search\FacilitiesSearch represents the model behind the search form about `common\models\Facilities`.
 */
 class FacilitiesSearch extends Facilities
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'facility_type_id', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['title', 'notes', 'created_at', 'updated_at'], 'safe'],
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
        $query = Facilities::find();
    
        // Get the current logged-in user's academy ID
        $userAcademy = Yii::$app->controller->academyMainObj->id;
    
        // Add a condition to filter by the logged-in user's academy
        $query->andFilterWhere([
            'academy_id' => $userAcademy,
        ]);
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        $this->load($params);
    
        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
    
        // Apply additional filters if provided in the search form
        $query->andFilterWhere([
            'id' => $this->id,
            'facility_type_id' => $this->facility_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
    
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'notes', $this->notes]);
    
        return $dataProvider;
    }
    
}
