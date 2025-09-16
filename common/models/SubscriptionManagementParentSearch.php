<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subscription;

/**
 * common\models\SubscriptionManagementParentSearch represents the model behind the search form about `common\models\Subscription`.
 */
class SubscriptionManagementParentSearch extends Subscription
{
    public $parentName;
    public $academy_id;
    public $mobile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_by', 'updated_by'], 'integer'],
            [[
                'price_before_discount',
                'price_after_discount',
                'tax_value',
                'total_price',
                'amount_paid',
                'remaining_amount',
                'discount'
            ], 'number'],
            [[
                'payment_status',
                'subscription_status',
                'start_date',
                'end_date',
                'created_at',
                'updated_at',
                'parentName',
                'mobile'
            ], 'safe'],
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

    public function search($params, $academyId = null)
    {
        $query = Subscription::find();
        $query->joinWith(['parentUser']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->parentName) {
            $query->andWhere(['subscription.parent_id' => $this->parentName]);
        }

        $query->andFilterWhere([
            'subscription.parent_id' => $this->parent_id,
        ]);

        if ($this->mobile) {
            $query->andFilterWhere(['like', 'user.mobile', $this->mobile]);
        }


        if ($this->payment_status !== null || $this->subscription_status !== null) {
            $query->andFilterWhere([
                'subscription.payment_status' => $this->payment_status,
            ])->andFilterWhere([
                'subscription.subscription_status' => $this->subscription_status,
            ]);
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

        return $dataProvider;
    }



    public static function getPaymentStatusList()
    {
        return [
            1 => 'Paid',
            2 => 'Pending',
            3 => 'Failed',
            4 => 'Refunded',
        ];
    }
}
