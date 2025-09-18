<?php

use yii\db\Migration;

/**
 * Handles the creation of table `invoices`.
 */
class m250201_000006_create_invoices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('invoices', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->comment('Linked to users.id'),
            'payment_id' => $this->integer()->notNull()->comment('Linked to payments.id'),
            'invoice_number' => $this->string(50)->unique()->notNull()->comment('Invoice number'),
            'amount' => $this->decimal(10, 2)->notNull()->comment('Invoice amount'),
            'tax_amount' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('Tax amount'),
            'total_amount' => $this->decimal(10, 2)->notNull()->comment('Total amount'),
            'status' => $this->string(20)->notNull()->comment('Invoice status: draft|sent|paid|overdue'),
            'due_date' => $this->date()->notNull()->comment('Payment due date'),
            'paid_date' => $this->date()->null()->comment('Payment date'),
            'pdf_path' => $this->string(500)->null()->comment('Invoice PDF path'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Invoice creation'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);



       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('invoices');
    }
}