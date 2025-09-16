<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CoachAttendance;


use common\models\User;
use common\models\Academies;
use common\models\UserProfile;


/**
 * common\models\search\CoachAttendanceSearch represents the model behind the search form about `common\models\CoachAttendance`.
 */
 class CoachAttendanceSearch extends CoachAttendance
{
    public $mobile;
    public $fullName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'day'], 'integer'],
            [['attendance', 'departure', 'mobile'], 'safe'],
            [['date'], 'default', 'value' => null],
            ['fullName', 'string'],
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
        $query = CoachAttendance::find();
    
        // Use the userData() method to get the filtered coaches' IDs
        $coaches = \common\helpers\Helper::userData(User::USER_TYPE_TRAINER, 'user_profile.user_id', 'firstname AS full_name', 'firstname');
        $coachIds = array_column($coaches, 'user_id'); // Extract user IDs from the returned array
    
        // Apply the filter for user_id based on the coaches from the academy
        $query->andWhere(['coach_attendance.user_id' => $coachIds]);
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['coach_attendance.id' => SORT_DESC]), // Reverse order by 'id'
        ]);
    
        $this->load($params);
    
        if (!$this->validate()) {
            $query->where('0=1'); // Ensures no records are returned on validation failure
            return $dataProvider;
        }
    
        if (!empty($this->date)) {
            // Format the date to match the database format (Y-m-d)
            $formattedDate = date('Y-m-d', strtotime($this->date));
            $query->andFilterWhere(['=', 'coach_attendance.date', $formattedDate]);
        }

        if (!empty($this->attendance)) {
            // Format the time correctly if needed
            $formattedTime = date('H:i:s', strtotime($this->attendance));
            $query->andFilterWhere(['=', 'coach_attendance.attendance', $formattedTime]);
        }

        if (!empty($this->departure)) {
            // Format the time correctly if needed
            $formattedTime = date('H:i:s', strtotime($this->departure));
            $query->andFilterWhere(['=', 'coach_attendance.departure', $formattedTime]);
        }

        if (!empty($this->mobile)) {
            $query->joinWith(['user' => function ($q) {
                $q->andFilterWhere(['like', 'user.mobile', $this->mobile]);
            }]);
        }

        if (!empty($this->fullName)) {
            $query->joinWith(['userProfile' => function ($q) {
                $q->andFilterWhere(['like', 'user_profile.firstname', $this->fullName]);
            }]);
        }

        // Other conditions
        $query->andFilterWhere([
            'coach_attendance.id' => $this->id,
            'coach_attendance.user_id' => $this->user_id,
            'coach_attendance.day' => $this->day,
        ]);
    
        return $dataProvider;
    }

}
