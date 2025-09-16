<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\Payments;
use common\models\RentPayments;
use common\models\PaymentsStore;

class PaymentsSearch extends Model
{
    public $type; 
    public $parentName; 
    public $paymentNote; 
    public $from_date; 
    public $to_date; 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'parentName', 'paymentNote'], 'string'],
            [['from_date', 'to_date'], 'safe'],
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
     *
     * @param array $params
     * @param int $academyId
     * @return ArrayDataProvider
     */
    public function search($params, $academyId)
    {
        $this->load($params);

        $subscriptions = Payments::find()
            ->with(['subscription.parent'])
            ->innerJoin('subscription s', 's.id = payments.subscription_id')
            ->where(['s.academy_id' => $academyId])
            ->orderBy(['payments.created_at' => SORT_DESC])
            ->all();

        $rentals = RentPayments::find()
            ->with(['rent.facilities'])
            ->innerJoin('rent r', 'r.id = rent_payments.rent_id')
            ->where(['r.academy_id' => $academyId])
            ->orderBy(['rent_payments.created_at' => SORT_DESC])
            ->all();

        $products = PaymentsStore::find()
            ->with(['order.orderDetails.product'])
            ->innerJoin('orders o', 'o.id = payments_store.order_id')
            ->where(['o.academy_id' => $academyId])
            ->orderBy(['payments_store.created_at' => SORT_DESC])
            ->all();

        $allPayments = [];
        foreach ($subscriptions as $payment) {
            $allPayments[] = [
                'type' => 'اشتراك',
                'model' => $payment,
                'created_at' => $payment->created_at,
                'parentName' => $payment->subscription->parent->firstname ?? '',
                'paymentNote' => $payment->note ?? '',
            ];
        }
        foreach ($rentals as $payment) {
            $allPayments[] = [
                'type' => 'إيجار',
                'model' => $payment,
                'created_at' => $payment->created_at,
                'parentName' => $payment->rent->parent->firstname ?? '',
                'paymentNote' => $payment->note ?? '',
            ];
        }
        foreach ($products as $payment) {
            $allPayments[] = [
                'type' => 'منتج',
                'model' => $payment,
                'created_at' => $payment->created_at,
                'parentName' => $payment->order->parent->firstname ?? '',
                'paymentNote' => $payment->note ?? '',
            ];
        }

        if ($this->type) {
            $allPayments = array_filter($allPayments, function ($payment) {
                return $payment['type'] === $this->type;
            });
        }

        if ($this->parentName) {
            $allPayments = array_filter($allPayments, function ($payment) {
                return stripos($payment['parentName'], $this->parentName) !== false;
            });
        }

        if ($this->paymentNote) {
            $allPayments = array_filter($allPayments, function ($payment) {
                return stripos($payment['paymentNote'], $this->paymentNote) !== false;
            });
        }

        if ($this->from_date && $this->to_date) {
            $allPayments = array_filter($allPayments, function ($payment) {
                return strtotime($payment['created_at']) >= strtotime($this->from_date) &&
                       strtotime($payment['created_at']) <= strtotime($this->to_date);
            });
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allPayments,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['created_at'],
                'defaultOrder' => ['created_at' => SORT_DESC]
            ]
        ]);

        return $dataProvider;
    }
}