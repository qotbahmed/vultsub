<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payments`.
 */
class m250201_000004_create_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payments', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->comment('Linked to users.id'),
            'subscription_id' => $this->integer()->notNull()->comment('Linked to subscriptions.id'),
            'plan_id' => $this->integer()->notNull()->comment('Linked to subscription_plans.id'),
            'amount' => $this->decimal(10, 2)->notNull()->comment('Payment amount'),
            'currency' => $this->string(3)->notNull()->defaultValue('SAR')->comment('Payment currency'),
            'payment_method' => $this->string(50)->notNull()->comment('Payment method'),
            'transaction_id' => $this->string(255)->unique()->comment('Gateway transaction ID'),
            'status' => $this->string(20)->notNull()->comment("Payment status: pending|completed|failed|refunded"),
            'gateway_response' => $this->json()->null()->comment('Gateway response data'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Payment creation'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

      
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payments');
    }
}