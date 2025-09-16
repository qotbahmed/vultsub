<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SchedulesPlayer;
use common\models\Subscription;
use common\models\UserProfile;
use common\models\SubscriptionDetails;
use Yii;

class PlayerReportSearch extends Model
{
    public $fullName;
    public $sport_name;
    public $package_name;

    public function rules()
    {
        return [
            [['fullName', 'sport_name', 'package_name'], 'safe'],
        ];
    }

    /**
     * Search for scheduled players
     *
     * @param array $params
     * @return ActiveDataProvider
     */



    public function search($params)
    {
        $userAcademy = Yii::$app->controller->academyMainObj->id;
        $this->load($params);

        $playerIds = [];
        if (!empty($this->fullName)) {
            $playerIds = UserProfile::find()
                ->select('user_id')
                ->where(['like', 'firstname', $this->fullName])
                ->column();
        }

        $scheduledQuery = SchedulesPlayer::find()
            ->alias('sp')
            ->joinWith([
                'player.userProfile up',
                'academySport.sport s',
                'subscription.subscriptionDetails sd' => function ($q) {
                    $q->from(['sd' => SubscriptionDetails::tableName()]);
                }
            ])
            ->where(['sp.academy_id' => $userAcademy])
            ->andWhere([
                'or',
                ['sub.subscription_status' => Subscription::STATUS_ACTIVE],
                ['sub.subscription_status' => Subscription::STATUS_NEAR_EXPIRY],
            ]);

        if (!empty($this->sport_name)) {
            $scheduledQuery->andWhere(['sd.sport_name' => $this->sport_name]);
        }
        if (!empty($this->package_name)) {
            $scheduledQuery->andWhere(['sd.package_name' => $this->package_name]);
        }
        if (!empty($playerIds)) {
            $scheduledQuery->andWhere(['sp.player_id' => $playerIds]);
        }

        $unscheduledQuery = SubscriptionDetails::find()
            ->alias('sd')
            ->joinWith(['subscription sub'])
            ->where(['sub.academy_id' => $userAcademy])
            ->andWhere([
                'not exists',
                SchedulesPlayer::find()
                    ->where('sd.player_id = sp.player_id')
                    ->andWhere('sd.sports_id = sp.academy_sport_id')
            ]);

        if (!empty($this->sport_name)) {
            $unscheduledQuery->andWhere(['sd.sport_name' => $this->sport_name]);
        }
        if (!empty($this->package_name)) {
            $unscheduledQuery->andWhere(['sd.package_name' => $this->package_name]);
        }
        if (!empty($playerIds)) {
            $unscheduledQuery->andWhere(['sd.player_id' => $playerIds]);
        }

        return [
            'scheduledPlayersProvider' => new ActiveDataProvider([
                'query' => $scheduledQuery,
                'pagination' => ['pageSize' => 20],
                'distinct' => true                        
            ]),
            'unscheduledPlayersProvider' => new ActiveDataProvider([
                'query' => $unscheduledQuery,
                'pagination' => ['pageSize' => 20],
                'distinct' => true
            ]),
        ];
    }
}
