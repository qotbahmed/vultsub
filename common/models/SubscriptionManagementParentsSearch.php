<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subscription;

/**
 * common\models\SubscriptionManagementParentsSearch represents the model behind the search form about `common\models\Subscription`.
 */
class SubscriptionManagementParentsSearch extends Subscription
{
    public $parentName;
    public $academy_id;
    public $mobile;
    public $paymentNote;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_by', 'updated_by'], 'integer'],
            [['price_before_discount', 'price_after_discount', 'tax_value', 'total_price', 'amount_paid', 'remaining_amount', 'discount'], 'number'],
            [['payment_status', 'subscription_status', 'start_date', 'end_date', 'created_at', 'updated_at', 'parentName', 'mobile', 'paymentNote'], 'safe'],
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
        $query = Subscription::find()
            ->alias('subscription')
            ->leftJoin('user', 'user.id = subscription.parent_id')
            ->where(['subscription.subscription_status' => 5])
            ->with('latestPayment');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['subscription.created_at' => SORT_DESC],
                'attributes' => [
                    'subscription.created_at' => [
                        'asc' => ['subscription.created_at' => SORT_ASC],
                        'desc' => ['subscription.created_at' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                ],
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->parentName) {
            $query->andWhere(['subscription.parent_id' => $this->parentName]);
        }
        if ($this->mobile) {
            $query->andFilterWhere(['like', 'user.mobile', $this->mobile]);
        }
        if ($this->payment_status || $this->subscription_status) {
            $query->andFilterWhere(['like', 'subscription.payment_status', $this->payment_status])
                ->andFilterWhere(['like', 'subscription.subscription_status', $this->subscription_status]);
        }

        $currentAcademyId = Yii::$app->session->get('current_academy_id')
            ?? Yii::$app->user->identity->userProfile->academy_id;
        if ($currentAcademyId !== null) {
            $query->andWhere(['subscription.academy_id' => $currentAcademyId]);
        }

        if ($this->paymentNote) {
            $query->andFilterWhere(['like', 'payments.note', $this->paymentNote]);
        }

        return $dataProvider;
    }
}
