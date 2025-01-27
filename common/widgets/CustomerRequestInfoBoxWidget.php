<?php

namespace common\widgets;

use common\models\CustomerRequest;
use Yii;
use yii\base\Widget;

class CustomerRequestInfoBoxWidget extends Widget
{
    public $icon;
    public $color;
    public $from;
    public $to;

    public function run()
    {
        $count = $this->getUserType();

        return $this->renderInfoBox($count,  Yii::t('backend', 'Total Requests'));
    }

    private function getUserType()
    {
        $query = CustomerRequest::find(); // Default to customer

        if ($this->from && $this->to) {
            $query->andWhere(['between', 'created_at', strtotime($this->from), strtotime($this->to)]);
        }

        return $query->count();
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
