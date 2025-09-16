<?php
namespace common\models;

use Yii;

class ReportFilter extends  \yii\db\ActiveRecord
{
    public $startDate;
    public $endDate;
    public $created_at;
    public $receiptNumber;


    public function rules()
    {
        return [
            [['startDate', 'endDate'], 'required'],
            [['startDate', 'endDate'], 'date', 'format' => 'php:Y-m-d'],
            [['receiptNumber'], 'string', 'max' => 255],
            [['startDate', 'endDate', 'created_at', 'receiptNumber'], 'trim'],
            
        ];
    }
    public function attributeLabels()
    {
        return [
            'startDate' => Yii::t('common', 'Start Date'),
            'endDate' => Yii::t('common', 'End Date'),
            'receiptNumber' => Yii::t('common', 'Receipt Number'),
         
        ];
    }
    public function init()
    {
        parent::init();
        
        $this->startDate = date('Y-m-d');
        $this->endDate = date('Y-m-d');
        $this->receiptNumber = '';
    }
    
}