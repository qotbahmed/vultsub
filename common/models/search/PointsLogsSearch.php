<?php

namespace common\models\search\;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PointsLogs;

/**
 * common\models\search\\PointsLogsSearch represents the model behind the search form about `common\models\PointsLogs`.
 */
 class PointsLogsSearch extends PointsLogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'points_num', 'page_num', 'time', 'created_by', 'updated_by'], 'integer'],
            [['user_name', 'user_mobile', 'type', 'created_at', 'updated_at'], 'safe'],
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
        $query = PointsLogs::find();

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
            'user_id' => $this->user_id,
            'points_num' => $this->points_num,
            'page_num' => $this->page_num,
            'time' => $this->time,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_mobile', $this->user_mobile])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
