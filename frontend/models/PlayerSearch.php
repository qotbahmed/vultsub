<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Player;

/**
 * PlayerSearch represents the model behind the search form of `frontend\models\Player`.
 */
class PlayerSearch extends Model
{
    public $id;
    public $academy_id;
    public $name;
    public $email;
    public $phone;
    public $date_of_birth;
    public $sport;
    public $level;
    public $status;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'academy_id'], 'integer'],
            [['name', 'email', 'phone', 'date_of_birth', 'sport', 'level', 'status', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Player::find();

        // add conditions that should always apply here
        if ($this->academy_id) {
            $query->andWhere(['academy_id' => $this->academy_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
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
            'academy_id' => $this->academy_id,
            'date_of_birth' => $this->date_of_birth,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'sport', $this->sport])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
