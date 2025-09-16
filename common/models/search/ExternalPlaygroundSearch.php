<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExternalPlayground;

/**
 * common\models\search\ExternalPlaygroundSearch represents the model behind the search form about `common\models\ExternalPlayground`.
 */
class ExternalPlaygroundSearch extends ExternalPlayground
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'academy_id', 'academy_sport_id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['date', 'start_time', 'end_time', 'location', 'description', 'created_at', 'updated_at'], 'safe'],
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
        $query = ExternalPlayground::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->date)) {
            $normalizedDate = date('Y-m-d', strtotime($this->date));
            $query->andFilterWhere(['date' => $normalizedDate]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'academy_id' => $this->academy_id,
            'academy_sport_id' => $this->academy_sport_id,
            'status' => $this->status,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
