<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TrainerAttendance;

class TrainerAttendanceSearch extends TrainerAttendance
{
    public $firstname; // Virtual attribute for filtering by first name

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'academy_id'], 'integer'],
            [['date', 'comments', 'firstname'], 'safe'], // Add 'firstname' to safe attributes
            [['hours'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = TrainerAttendance::find();
        // Join with user_profile to enable filtering by firstname
        $query->joinWith(['userProfile']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        // Sort by firstname
        $dataProvider->sort->attributes['firstname'] = [
            'asc' => ['user_profile.firstname' => SORT_ASC],
            'desc' => ['user_profile.firstname' => SORT_DESC],
        ];
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        // Convert the date to YYYY-MM-DD format if it is set
        if ($this->date) {
            // Define a mapping of Arabic month names to their numeric equivalents
            $arabicMonths = [
                'يناير' => '01',
                'فبراير' => '02',
                'مارس' => '03',
                'أبريل' => '04',
                'مايو' => '05',
                'يونيو' => '06',
                'يوليو' => '07',
                'أغسطس' => '08',
                'سبتمبر' => '09',
                'أكتوبر' => '10',
                'نوفمبر' => '11',
                'ديسمبر' => '12',
            ];
            // Convert the date to the format 'YYYY-MM-DD'
            $dateParts = explode('-', $this->date);
            if (count($dateParts) === 3) {
                $day = trim($dateParts[0]);
                $monthName = trim($dateParts[1]); // This will be the Arabic month name
                $year = trim($dateParts[2]);
                // Get the numeric month
                $month = $arabicMonths[$monthName] ?? null;
                if ($month) {
                    $this->date = "$year-$month-$day";
                }
            }
        }
        // Filter conditions
        $query->andFilterWhere([
            'trainer_attendance.id' => $this->id,
            'trainer_attendance.user_id' => $this->user_id,
            'trainer_attendance.date' => $this->date,
            'trainer_attendance.hours' => $this->hours,
            'trainer_attendance.academy_id' => Yii::$app->controller->academyMainObj->id,
        ]);
        // Filter by comments
        $query->andFilterWhere(['like', 'trainer_attendance.comments', $this->comments]);
        // Filter by firstname from user_profile
        $query->andFilterWhere(['like', 'user_profile.firstname', $this->firstname]);
        return $dataProvider;
    }

}
