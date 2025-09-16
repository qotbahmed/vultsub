<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Communication;

/**
 * common\models\search\CommunicationSearch represents the model behind the search form about `common\models\Communication`.
 */
 class CommunicationSearch extends Communication
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'communication_type', 'send_method', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['message_text', 'created_at', 'updated_at'], 'safe'],
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
        $query = Communication::find();

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
            'communication_type' => $this->communication_type,
            'send_method' => $this->send_method,
            'academy_id' => $this->academy_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'message_text', $this->message_text]);

        return $dataProvider;
    }
}
