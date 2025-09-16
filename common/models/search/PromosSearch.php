<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Promos;

/**
 * common\models\search\PromosSearch represents the model behind the search form about `common\models\Promos`.
 */
 class PromosSearch extends Promos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount', 'academy_id', 'percentage'], 'integer'],
            [['name', 'created_at', 'updated_at', 'discount_type'], 'safe'],
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
        $query = Promos::find()->where(['!=', 'status', Promos::STATUS_BY_ADMIN]); 


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
     
    
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'academy_id' => $this->academy_id,
            'percentage' => $this->percentage,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'discount_type', $this->discount_type]);

        return $dataProvider;
    }
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (Yii::$app->controller->academyMainObj->id) {
                $this->academy_id = Yii::$app->controller->academyMainObj->id;
            }
            return true;
        }
        return false;
    }
}
