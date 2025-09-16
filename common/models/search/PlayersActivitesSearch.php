<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PlayersActivites;
use common\models\User;

/**
 * common\models\search\PlayersActivitesSearch represents the model behind the search form about `common\models\PlayersActivites`.
 */
class PlayersActivitesSearch extends PlayersActivites
{
    public $fullName;
    public $player_id;
    public $sport_name;
    public $identification_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sport_id', 'player_id', 'level_id', 'academy_id'], 'integer'],
            [['fullName', 'sport_name', 'identification_number'], 'safe'],

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
        $this->load($params);

        if (!$this->validate()) {
            return new ActiveDataProvider([
                'query' => PlayersActivites::find()->where('0=1'),
            ]);
        }

        if (User::find()->where(['user_type' => User::USER_TYPE_PLAYER])) {
            $controller = Yii::$app->controller;
            $academyId = $controller->academyMainObj->id;

            $query = (new \yii\db\Query())
                ->select([
                    'pa.player_id',
                    'GROUP_CONCAT(DISTINCT sport.id ORDER BY sport.id ASC SEPARATOR ",") as sports_ids',
                    'user_profile.firstname as player_firstname',
                    'MAX(behavior_log.end_date) as last_evaluation_date',
                    'MAX(medical_log.end_date) as last_medical_evaluation_date',
                    'MAX(behavior_log.assignment_status) as last_evaluation_statuss',
                    'MAX(medical_log.assignment_status) as last_evaluation_status',
                    'MAX(CASE WHEN s.parent_id = pa.player_id THEN CONCAT(COALESCE(user_profile.firstname, ""), " ", COALESCE(user_profile.lastname, "")) ELSE CONCAT(COALESCE(parent_profile.firstname, ""), " ", COALESCE(parent_profile.lastname, "")) END) as parent_fullname',
                    'MAX(CASE WHEN s.parent_id = pa.player_id THEN 1 ELSE 0 END) as is_parent_player',
                    'MAX(pa.id) as max_id',
                    'parent_profile.user_id as parent_id',
                    'parent_profile.firstname as parent_firstname',
                    'parent_profile.lastname as parent_lastname',
                ])
                ->from(['pa' => 'players_activites'])
                ->leftJoin('sport', 'pa.sport_id = sport.id')
                ->leftJoin('user', 'pa.player_id = user.id')
                ->leftJoin('user_profile', 'user.id = user_profile.user_id')
                ->leftJoin('subscription_details sd', 'sd.player_id = pa.player_id')
                ->leftJoin('subscription s', 's.id = sd.subscription_id AND s.academy_id = ' . (int)$academyId)
                ->leftJoin('user_profile parent_profile', 'parent_profile.user_id = s.parent_id')
                ->leftJoin('player_answer_behavior', 'player_answer_behavior.player_id = pa.player_id')
                ->leftJoin('behavior_log', 'behavior_log.player_answer_behavior_id = player_answer_behavior.id')
                ->leftJoin('player_answer_medical', 'player_answer_medical.player_id = pa.player_id')
                ->leftJoin('medical_log', 'medical_log.player_answer_medical_id = player_answer_medical.id')
                ->where(['pa.academy_id' => $academyId])
                ->groupBy(['pa.player_id', 'parent_profile.user_id'])
                ->orderBy(['max_id' => SORT_DESC]);

            if (!empty($this->academy_sport_id)) {
                $query->andWhere(['pa.sport_id' => $this->academy_sport_id]);
            }

            if (!empty($this->sport_name)) {
                $query->andWhere(['pa.sport_id' => $this->sport_name]);
            }

            if (!empty($this->player_id)) {
                $query->andWhere(['pa.player_id' => $this->player_id]);
            }

            if (!empty($this->identification_number)) {
                $query->andWhere(['user_profile.identification_number' => $this->identification_number]);
            }

            if (!empty($this->fullName)) {
                $query->andFilterWhere([
                    'or',
                    ['like', 'user_profile.firstname', $this->fullName],
                    ['like', 'user_profile.lastname', $this->fullName],
                ]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $models = $dataProvider->getModels();
            foreach ($models as &$model) {
                $sports = [];
                if (!empty($model['sports_ids'])) {
                    $ids = explode(',', $model['sports_ids']);
                    foreach ($ids as $id) {
                        $sport = \common\models\Sport::findOne($id);
                        if ($sport) {
                            $sports[] = $sport->getLocalizedTitle();
                        }
                    }
                }
                $model['sports'] = implode(', ', $sports);
            }
            $dataProvider->setModels($models);

            return $dataProvider;
        } else {
            $query = PlayersActivites::find()
                ->select([
                    'player_id',
                    'sport_id',
                    'MAX(level_id) as level_id',
                    'MAX(id) as id'
                ])
                ->groupBy(['player_id', 'sport_id']);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);

            if (!$this->validate()) {
                return $dataProvider;
            }

            $query->andFilterWhere([
                'id' => $this->id,
                'player_id' => $this->player_id,
                'sport_id' => $this->sport_id,
                'level_id' => $this->level_id,
                'academy_id' => $this->academy_id,
            ]);

            return $dataProvider;
        }
    }
}
