<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscriptions`.
 */
class m250201_000008_create_subscriptions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscriptions', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->comment('Linked to users.id'),
            'type' => $this->string(20)->notNull()->comment('Subscription type: trial|paid|expired'),
            'start_date' => $this->date()->notNull()->comment('Subscription start date'),
            'end_date' => $this->date()->notNull()->comment('Subscription end date'),
            'status' => $this->string(20)->notNull()->comment('Subscription status: active|expired|locked'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Subscription creation'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscriptions');
    }
}
