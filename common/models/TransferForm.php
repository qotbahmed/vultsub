<?php
namespace common\models;

use yii\base\Model;

class TransferForm extends Model
{
    public $amount;
    public $notes;
    public $type_of_safe;

    public function rules()
    {
        return [
            [['amount','type_of_safe'], 'required'],
            [['amount'], 'number', 'min' => 1],
            [['notes'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'المبلغ',
            'notes' => 'ملاحظات',
            'type_of_safe' => 'نوع الخزنة المحول إليها',
        ];
    }
}