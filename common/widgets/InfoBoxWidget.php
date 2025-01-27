<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use common\models\User;

class InfoBoxWidget extends Widget
{
    public $icon;
    public $color;
    public $type;
    public $from;
    public $to;

    public function run()
    {
        $count = $this->getUserType();

        return $this->renderInfoBox($count);
    }

    private function getUserType()
    {
        $query = User::find()->where(['user_type' => $this->type]); // Default to customer
        if ($this->type==User::USER_TYPE_PLAYER) {
            $query->andWhere(['user_type' => User::USER_TYPE_PLAYER])->andFilterWhere(['>', 'id', 3]);
        }

        if ($this->from && $this->to) {
            $query->andWhere(['between', 'created_at', strtotime($this->from), strtotime($this->to)]);
        }

        return $query->count();    }
    private function getTitel()
    {
        return [
            User::USER_TYPE_PLAYER => Yii::t('backend', 'Players'),
            User::USER_TYPE_PARENT => Yii::t('backend', 'Parent'),
            User::USER_TYPE_ACADEMY_ADMIN => Yii::t('backend', 'Academy Admin'),
            User::USER_TYPE_TRAINER => Yii::t('backend', 'Trainer'),
        ];    }

    private function renderInfoBox($userCount)
    {
        return <<<HTML
<div class="col-md-3 col-sm-6 col-12">
    <div class="info-box {$this->color}">
        <span class="info-box-icon"><i class="fas {$this->icon}"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">{$this->getTitel()[$this->type]}</span>
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
