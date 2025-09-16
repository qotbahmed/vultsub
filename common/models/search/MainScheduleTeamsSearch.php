<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MainScheduleTeams;
use common\models\Academies;

/**
 * common\models\search\MainScheduleTeamsSearch represents the model behind the search form about `common\models\MainScheduleTeams`.
 */
 class MainScheduleTeamsSearch extends MainScheduleTeams
{
    public $academyId;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sport_id', 'trainer_id', 'academy_id'], 'integer'],
            [['slug', 'team_name', 'day', 'start_time'], 'safe'],
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
        // Get the current academy
        $currentAcademy = Yii::$app->controller->academyMainObj;

        // Set academyId depending on whether the current academy is the main or sub-academy
        if (Yii::$app->controller->MainAcadmin) {
            // Main academy: include all sub-academies
            $this->academyId = $currentAcademy->id;
            $mainAcademy = Academies::findOne($this->academyId);
            $subAcademyIds = $mainAcademy->getAcademies()->select('id')->column();
            $academyIds = array_merge([$this->academyId], $subAcademyIds);
        } else {
            // Sub-academy: restrict to the current academy only
            $this->academyId = $currentAcademy->id;
            $academyIds = [$this->academyId];
        }

        $query = MainScheduleTeams::find()->where(['academy_id' => $academyIds]);

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
            'start_time' => $this->start_time,
            'sport_id' => $this->sport_id,
            'trainer_id' => $this->trainer_id,
            'academy_id' => $this->academy_id,
        ]);

        // $query->andFilterWhere(['like', 'slug', $this->slug])
        $query->andFilterWhere(['like', 'team_name', $this->team_name])
            ->andFilterWhere(['like', 'day', $this->day]);

        if (!empty($this->slug)) {
            $query->andFilterWhere([
                'or',
                ['like', 'team_name', $this->slug],
                ['slug' => $this->slug]
            ]);
        }

        return $dataProvider;
    }
}
