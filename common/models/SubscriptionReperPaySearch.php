<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Payments;

use Yii;

class SubscriptionReperPaySearch extends Model
{
    public $startDate;
    public $endDate;
    public $paymentType;
    public $revenueType; 
    public $name;


    public function rules()
    {
        return [
            [['startDate', 'endDate', 'paymentType', 'revenueType', 'name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'startDate' => Yii::t('common', 'Start Date'),
            'endDate' => Yii::t('common', 'End Date'),
            'paymentType' => Yii::t('common', 'Payment Type'),
            'revenueType' => Yii::t('common', 'Revenue Type'),
            'name' => Yii::t('common', 'Name'),
        ];
    }

    public function search($params)
    {
        $query = Payments::find()
            ->with(['subscription.parent'])
            ->innerJoin('subscription s', 's.id = payments.subscription_id')
            ->where(['s.academy_id' => Yii::$app->user->identity->academy_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->startDate && $this->endDate) {
            $query->andWhere(['between', 'payments.created_at', $this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }

        if ($this->paymentType) {
            $query->andWhere(['payments.payment_method' => $this->paymentType]);
        }

        if ($this->name) {
            $query->andWhere(['like', 's.parent_name', $this->name]);
        }
     
        return $dataProvider;
    }
}