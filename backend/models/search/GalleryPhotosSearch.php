<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\GalleryPhotos;

/**
 * backend\models\search\GalleryPhotosSearch represents the model behind the search form about `backend\models\GalleryPhotos`.
 */
 class GalleryPhotosSearch extends GalleryPhotos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gallery_id', 'size', 'sort'], 'integer'],
            [['path', 'base_url', 'type', 'name', 'title', 'header_one', 'header_two', 'header_three', 'has_more', 'url', 'heder_four', 'updated_at', 'created_at'], 'safe'],
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
        $query = GalleryPhotos::find();

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
            'gallery_id' => $this->gallery_id,
            'size' => $this->size,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'base_url', $this->base_url])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'header_one', $this->header_one])
            ->andFilterWhere(['like', 'header_two', $this->header_two])
            ->andFilterWhere(['like', 'header_three', $this->header_three])
            ->andFilterWhere(['like', 'has_more', $this->has_more])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'heder_four', $this->heder_four])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
}
