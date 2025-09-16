<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Academies;

/**
 * common\models\search\AcademiesSearch represents the model behind the search form about `common\models\Academies`.
 */

class SubAcademiesSearch extends Academies
{
    public $parent_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'manager_id', 'parent_id', 'created_by', 'updated_by','complete_profile'], 'integer'],
            [['title', 'contact_phone', 'description', 'contact_email',
                'logo_path', 'logo_base_url', 'address', 'location', 'lng', 'main', 'created_at', 'updated_at'], 'safe'],
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
     * @param integer $parent_id
     *
     * @return ActiveDataProvider
     */
    public function search($params, $parent_id = null)
    {
        $query = Academies::find()->where(['main' => 0]);

        if ($parent_id !== null) {
            $this->parent_id = $parent_id;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'manager_id' => $this->manager_id,
            'parent_id' => $this->parent_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'logo_path', $this->logo_path])
            ->andFilterWhere(['like', 'logo_base_url', $this->logo_base_url])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'lng', $this->lng])
            ->andFilterWhere(['like', 'main', $this->main]);

        return $dataProvider;
    }
}