<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Products;

/**
 * common\models\search\ProductsSearch represents the model behind the search form about `app\models\Products`.
 */
 class ProductsSearch extends Products
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'academy_id', 'sport_id', 'category_id', 'quantity', 'quantity_used', 'quantity_remaining', 'created_by', 'updated_by'], 'integer'],
            [['title', 'logo_base_url', 'logo_path', 'description', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
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
        $query = Products::find();

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
            'academy_id' => $this->academy_id,
            'sport_id' => $this->sport_id,
            'category_id' => $this->category_id,
            'quantity' => $this->quantity,
            'quantity_used' => $this->quantity_used,
            'quantity_remaining' => $this->quantity_remaining,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'logo_base_url', $this->logo_base_url])
            ->andFilterWhere(['like', 'logo_path', $this->logo_path])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
