<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TalentImages;

/**
 * common\models\search\TalentImagesSearch represents the model behind the search form about `common\models\TalentImages`.
 */
 class TalentImagesSearch extends TalentImages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'talent_id'], 'integer'],
            [['img', 'img_path', 'img_base_url', 'created_at', 'updated_at'], 'safe'],
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
        $query = TalentImages::find();
    
        // Get the current academy object from the controller
        $currentAcademy = Yii::$app->controller->academyMainObj;
    
        // Ensure academyMainObj is not null and has a valid ID
        if (!$currentAcademy || !isset($currentAcademy->id)) {
            throw new \yii\web\NotFoundHttpException('Current academy not found.');
        }
    
        // Filter by current academy ID
        $currentAcademyId = $currentAcademy->id;
        $query->andWhere(['academy_id' => $currentAcademyId]);
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        $this->load($params);
    
        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
    
        // Additional filters based on user input
        $query->andFilterWhere([
            'id' => $this->id,
            'talent_id' => $this->talent_id,
        ]);
    
        $query->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'img_path', $this->img_path])
            ->andFilterWhere(['like', 'img_base_url', $this->img_base_url])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
    
        return $dataProvider;
    }
    


}
