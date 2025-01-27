<?php

namespace backend\models;

use Yii;
use yii\base\Model;

class DateRangeForm extends Model
{
public $academy_id;

    public $from;
    public $to;

    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            ['to', 'compare', 'compareAttribute' => 'from', 'operator' => '>='],
            [['from', 'to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'from' => Yii::t('backend', 'From'),
            'to' => Yii::t('backend', 'To'),
        ];
    }

}