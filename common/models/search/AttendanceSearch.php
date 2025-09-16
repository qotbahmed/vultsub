<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Attendance;

/**
 * common\models\search\AttendanceSearch represents the model behind the search form about `common\models\Attendance`.
 */
 class AttendanceSearch extends Attendance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'schedule_id', 'user_id'], 'integer'],
            [['attendance_status', 'created_at'], 'safe'],
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
        $query = Attendance::find();

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
            'schedule_id' => $this->schedule_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'attendance_status', $this->attendance_status]);

        return $dataProvider;
    }
}
