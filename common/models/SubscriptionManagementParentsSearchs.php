<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subscription;

class SubscriptionManagementParentsSearchs extends Subscription
{
    public $parentName;
    public $academy_id;
    public $mobile;
    public $paymentNote;
    public $startDate;
    public $endDate;
    public $paymentType;
    public $revenueType;
    public $subscriptionType;
    public $discountAmount;
    public $paymentStatus;
    public $Receipt;



    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_by', 'updated_by'], 'integer'],
            [['price_before_discount', 'price_after_discount', 'tax_value', 'total_price', 'amount_paid', 'remaining_amount', 'discount', 'discountAmount'], 'number'],
            [['payment_status', 'subscription_status', 'start_date', 'end_date', 'created_at', 'updated_at', 'parentName', 'mobile', 'paymentNote', 'startDate', 'endDate', 'paymentType', 'revenueType', 'subscriptionType', 'paymentStatus', 'Receipt'], 'safe'],
        ];
    }

    public function search($params, $academyId = null)
    {
        // $query = Subscription::find();
        $query = Subscription::find()
            ->alias('subscription')
            ->leftJoin('user', 'user.id = subscription.parent_id')
            ->with('latestPayment');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20], // Set pagination
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->parentName) {
            $query->andFilterWhere(['like', 'user_profile.firstname', $this->parentName]);
        }

        if ($this->mobile) {
            $query->andFilterWhere(['like', 'user_profile.mobile', $this->mobile]);
        }

        if ($academyId !== null) {
            $query->andWhere([
                'or',
                ['subscription.academy_id' => $academyId],
                [
                    'in',
                    'subscription.academy_id',
                    \common\models\Academies::find()
                        ->select('id')
                        ->where(['parent_id' => $academyId])
                ]
            ]);
        }


        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->andWhere(['>=', 'subscription.start_date', $this->startDate . ' 00:00:00'])
                ->andWhere(['<=', 'subscription.end_date', $this->endDate . ' 23:59:59']);
        } elseif (!empty($this->startDate)) {
            $query->andWhere(['>=', 'subscription.start_date', $this->startDate . ' 00:00:00']);
        } elseif (!empty($this->endDate)) {
            $query->andWhere(['<=', 'subscription.end_date', $this->endDate . ' 23:59:59']);
        }
        if ($this->paymentType) {
            $query->andWhere(['subscription.payment_method' => $this->paymentType]);
        }

        if ($this->payment_status !== null && $this->payment_status !== '') {
            $query->andFilterWhere(['subscription.payment_status' => $this->payment_status]);
        }
        if ($this->subscriptionType) {
            $query->andWhere(['subscription.subscription_type' => $this->subscriptionType]);
        }

        if ($this->discountAmount) {
            $query->andWhere(['>=', 'subscription.discount', $this->discountAmount]);
        }
        // echo $query->createCommand()->rawSql;
        // exit;

        return $dataProvider;
    }

    public function searchForParentsReport($params, $academyId, $subQuery)
    {
        $query = Subscription::find()
            ->alias('subscription')
            ->joinWith(['parent p'])
            ->where(['subscription.id' => $subQuery])
            ->orderBy(['subscription.end_date' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->parentName)) {
            $query->andFilterWhere(['p.user_id' => $this->parentName]);
        }

        if (!empty($this->mobile)) {
            $query->andFilterWhere(['like', 'p.mobile', $this->mobile]);
        }

        if (!empty($this->subscription_status)) {
            $query->andFilterWhere(['subscription.subscription_status' => $this->subscription_status]);
        }

        return $dataProvider;
    }
}
