<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VitalLog;

/**
 * common\models\search\VitalLogSearch represents the model behind the search form about `common\models\VitalLog`.
 */
 class VitalLogSearch extends VitalLog
{
        public $player_id; 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vital_sign_id', 'updated_by', 'created_by'], 'integer'],
            [['notification_preference', 'assignment_status', 'start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
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
  

public function search($params, $player_id = null)
{
    $query = VitalLog::find()->joinWith('vitalSign')->distinct();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    if ($player_id !== null) {
        $query->andFilterWhere([
            'vital_sign.player_id' => $player_id,
        ]);
    }

    $query->andFilterWhere([
        'id' => $this->id,
        'vital_sign_id' => $this->vital_sign_id,
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'updated_by' => $this->updated_by,
        'created_by' => $this->created_by,
    ]);

    $query->andFilterWhere(['like', 'notification_preference', $this->notification_preference])
          ->andFilterWhere(['like', 'assignment_status', $this->assignment_status]);

    return $dataProvider;
}

}
