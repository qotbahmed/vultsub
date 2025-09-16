<?php
namespace common\models;
use Yii;

use yii\base\Model;

class PartnerContributionForm extends Model
{
    public $amount;
    public $type_of_safe;
    public $receipt_number;
    public $partner_id;


    public function rules()
    {
        return [
            [['amount', 'type_of_safe','partner_id'], 'required'],
            ['amount', 'number', 'min' => 0.01],
            ['type_of_safe', 'integer'],
            ['receipt_number', 'string', 'max' => 255],
        //     [['partner_id'], 'exist', 
        //     'targetClass' => Partner::class, 
        //     'targetAttribute' => 'id',
        //     'filter' => ['academy_id' => Yii::$app->user->identity->academy_id]
        // ],
[['partner_id'], 'exist', 'targetClass' => Partner::class, 'targetAttribute' => 'id'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'partner_id' => 'الممول',
            'amount' => Yii::t('common', 'Amount'),
            'type_of_safe' => Yii::t('common', 'Type of Safe'),
            'receipt_number' => Yii::t('common', 'Receipt Number'),
        ];
    }
}
