<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Rent;

class RentSearch extends Rent
{
    public $created_at;
    public $receipt_number;
    public $payment_method;   // ← new
    // total is already defined on the parent and validated as number

    public function rules()
    {
        return [
            [['id', 'academy_id', 'facility_id', 'facility_type_id', 'created_by', 'updated_by', 'payment_method'], 'integer'], // added payment_method
            [['sub_total', 'tax', 'total'], 'number'],

            [['statement', 'note', 'payment_status', 'owner', 'type', 'period', 'start_date', 'end_date', 'created_at', 'receipt_number'], 'safe'],
        ];
    }
    public function init()
    {
        parent::init();
        $this->payment_method = null;
    }


    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Rent::find()->where(['status' => 1]);
        $query->joinWith('rentPayments rp');
        $query->distinct();  // prevent duplicates when a rent has multiple payments

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // existing exact/like filters...
        $query->andFilterWhere([
            'id'               => $this->id,
            'start_date'       => $this->start_date,
            'end_date'         => $this->end_date,
            'sub_total'        => $this->sub_total,
            'tax'              => $this->tax,
            'total'            => $this->total,            // ← filters by exact total
            'academy_id'       => $this->academy_id,
            'facility_id'      => $this->facility_id,
            'facility_type_id' => $this->facility_type_id,
            'created_by'       => $this->created_by,
            'updated_by'       => $this->updated_by,
        ])
            ->andFilterWhere(['like', 'statement', $this->statement])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'owner', $this->owner])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'period', $this->period]);

        if (!empty($this->created_at)) {
            $query->andFilterWhere(['like', 'rent.created_at', $this->created_at]);
        }

        if ($this->receipt_number !== null && $this->receipt_number !== '') {
            $query->andFilterWhere(['=', 'rp.receipt_number', $this->receipt_number]);
        }

        // ← new: payment method filter
        if ($this->payment_method !== null && $this->payment_method !== '') {
            $query->andFilterWhere(['rp.payment_method' => $this->payment_method]);
        }

        return $dataProvider;
    }
}
