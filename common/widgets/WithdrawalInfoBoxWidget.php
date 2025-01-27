<?php

namespace common\widgets;

use common\models\CustomerRequest;
use common\models\Payment;
use common\models\Withdrawal;
use Yii;
use yii\base\Widget;
use common\models\User;

class WithdrawalInfoBoxWidget extends Widget
{
    public $icon;
    public $color;
    public $from;
    public $to;

    public function run()
    {
        $count = $this->getUserType();

        return $this->renderInfoBox($count,  Yii::t('backend', 'Total Withdrawal'));
    }

    private function getUserType()
    {
        $query =  Withdrawal::find();

        if ($this->from && $this->to) {
            $query->andWhere(['between', 'created_at', strtotime($this->from), strtotime($this->to)]);
        }
        $totalAmount = $query->sum('total');

        return $totalAmount;
    }



    private function renderInfoBox($userCount,$title)
    {
        return <<<HTML
<div class="col-md-3 col-sm-6 col-12">
    <div class="info-box {$this->color}">
        <span class="info-box-icon"><i class="fas {$this->icon}"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">{$title}</span>
            <span class="info-box-number">{$userCount}</span>
            <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
            </div>
        </div>
    </div>
</div>
HTML;
    }
}
