<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Schedules;

/**
 * common\models\search\SchedulesSearch represents the model behind the search form about `common\models\Schedules`.
 */
class SchedulesSearch extends Schedules
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'capacity', 'academy_id', 'coach_id', 'academy_sport_id', 'age_group_id', 'created_by', 'updated_by'], 'integer'],
            [['title', 'gender', 'day', 'start_time', 'end_time', 'level', 'created_at', 'updated_at'], 'safe'],
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
        $query = Schedules::find();

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

        $query->andFilterWhere([
            'id' => $this->id,
            'start_time' => !empty($this->start_time) ? date("H:i:s", strtotime($this->start_time)) : null,
            'end_time' => !empty($this->end_time) ? date("H:i:s", strtotime($this->end_time)) : null,
            'capacity' => $this->capacity,
            'academy_id' => $this->academy_id,
            'coach_id' => $this->coach_id,
            'academy_sport_id' => $this->academy_sport_id,
            'age_group_id' => $this->age_group_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['like', 'level', $this->level]);

        return $dataProvider;
    }
}
