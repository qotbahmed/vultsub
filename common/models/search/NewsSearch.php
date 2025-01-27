<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;

/**
 * common\models\search\NewsSearch represents the model behind the search form about `common\models\News`.
 */
 class NewsSearch extends News
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'academy_id'], 'integer'],
            [['title', 'description', 'image_path', 'image_base_url', 'status', 'intro', 'created_at'], 'safe'],
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
        $query = News::find();

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
            // 'academy_id' => $this->academy_id,
            'academy_id' => Yii::$app->controller->academyMainObj->id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_path', $this->image_path])
            ->andFilterWhere(['like', 'image_base_url', $this->image_base_url])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'intro', $this->intro]);

        return $dataProvider;
    }
}
