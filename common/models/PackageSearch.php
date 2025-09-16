<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Package;

/**
 * common\models\PackageSearch represents the model behind the search form about `common\models\Package`.
 */
class PackageSearch extends Package
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sport_id', 'classes', 'amount', 'academy_id', 'updated_by', 'created_by'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
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
     * @param integer|array $academyId
     *
     * @return ActiveDataProvider
     */
    public function search($params, $academyId = null)
    {
        $query = Package::find();
    
        // Adjust the query for multiple academy IDs
        if ($academyId !== null) {
            // Ensure $academyId is an array for the `in` condition
            $query->andWhere(['in', 'academy_id', (array)$academyId]);
        }
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        // Load parameters into the search model
        $this->load($params);
    
        // Return data provider if validation fails
        if (!$this->validate()) {
            return $dataProvider;
        }
    
        // Apply additional filters
        $query->andFilterWhere([
            'id' => $this->id,
            'sport_id' => $this->sport_id,
            'classes' => $this->classes,
            'amount' => $this->amount,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
    
        $query->andFilterWhere(['like', 'name', $this->name]);
    
        return $dataProvider;
    }
}
