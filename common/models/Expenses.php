<?php

namespace common\models;

use Yii;
use \common\models\base\Expenses as BaseExpenses;

/**
 * This is the model class for table "expenses".
 */
class Expenses extends BaseExpenses
{


public function afterSave($insert, $changedAttributes)
{
    parent::afterSave($insert, $changedAttributes);

    if ($insert) {
        $this->recordExpenseTransaction();
    }
}

public function recordExpenseTransaction()
{
    $academyId = $this->academy->id ?? null;

    if (!$academyId) {
        Yii::error("Academy ID not found for payment #{$this->id}");
        return;
    }

    $financialBalance = FinancialBalance::findOrCreate(['academy_id' => $academyId]);

    $typeOfSafe = $this->treasury == self::TREASURY_CASH 
        ? Transactions::SAFE_TYPE_CASH 
        : Transactions::SAFE_TYPE_BANK;
    
    $financialBalance->updateBalance(-$this->amount, $typeOfSafe, 'expenses');
    
    $this->createTransaction($financialBalance, $typeOfSafe);
}

public function createTransaction($financialBalance, $typeOfSafe)
{
    $prefixes = Transactions::getReceiptPrefixes();
    $prefix = isset($prefixes[Transactions::SOURCE_EXPENSE]) ? $prefixes[Transactions::SOURCE_EXPENSE] : '';
    $formattedReceipt = $prefix . $this->receipt_number;
    $transaction = new Transactions([
        'academy_id' => $this->academy_id,
        'type' => Transactions::TYPE_OUTGOING, 
        'source' => Transactions::SOURCE_EXPENSE, 
        'source_id' => $this->id, 
        'amount' => -$this->amount, 
        'financial_balance_id' => $financialBalance->id, 
        'type_of_safe' => $typeOfSafe, 
        'receipt_number' => $formattedReceipt,
        'note' => $this->description ?? '-', 
        'created_at' => date('Y-m-d H:i:s'), 
        'updated_at' => date('Y-m-d H:i:s'), 
        'created_by' => Yii::$app->user->id,
        'updated_by' => Yii::$app->user->id,
    ]);

    $transaction->save(false);
}

	
}
