<?php

namespace frontend\models;

use common\models\Subscription;
use Yii;

class SubscriptionForm extends \yii\base\Model
{

    public $subscription_id;
    public $player_id;
    public $start_date;
    public $close=0;
    public $sport_id;
    public $error;
    public $packages_id;

    public function rules()
    {
        return [
            [[ 'player_id','start_date','sport_id','packages_id'], 'required'],
            [['subscription_id'], 'integer'],
            [['start_date','error','close'], 'safe'],
            [['player_id', 'sport_id', 'packages_id'], 'integer'],
        ];
    }

    public function initSubscription()
    {
        $subscription = new Subscription();
        $subscription->parent_id = Yii::$app->user->id;
        $subscription->academy_id = Yii::$app->user->identity->userProfile->academy_id;
        $subscription->start_date = $this->start_date;
        $end_date = date('Y-m-d', strtotime('+1 month', strtotime( $subscription->start_date)));

        $subscription->end_date = $end_date;
        $subscription->price_before_discount = 0;
        $subscription->price_after_discount = 0;
        $subscription->tax_value = 0;
        $subscription->total_price = 0;
        $subscription->amount_paid = 0;
        $subscription->discount = 0;
        $subscription->remaining_amount = 0;
        $subscription->payment_status = 2;
        $subscription->subscription_status = 4;
        $subscription->notification_preference = 1;


        if (!$subscription->save()) {
            return null;
        }
        $subscription->calculateTotalPrice();
        return $subscription->id;

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'parent_id' => Yii::t('common', 'Parent ID'),
            'player_id' => Yii::t('common', 'Child'),
            'academy_id' => Yii::t('common', 'Academy'),

            'start_date' => Yii::t('common', 'Start Date'),
            'end_date' => Yii::t('common', 'End Date'),


        ];
    }


}