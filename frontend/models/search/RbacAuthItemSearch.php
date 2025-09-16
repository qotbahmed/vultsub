<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\rbac\models\RbacAuthItem;

/**
 * backend\models\search\BankSearch represents the model behind the search form about `backend\models\Bank`.
 */
 class RbacAuthItemSearch extends RbacAuthItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','assignment_category'], 'integer'],
            [['name','description','assignment_category'], 'safe'],
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
        $query = RbacAuthItem::find();
            

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            // return $dataProvider;
        }

        $query->andFilterWhere([
            'type' => $this->type,
            'assignment_category' => $this->assignment_category
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
