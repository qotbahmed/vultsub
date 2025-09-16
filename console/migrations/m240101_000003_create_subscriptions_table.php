<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscriptions`.
 */
class m240101_000003_create_subscriptions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscriptions', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'plan_id' => $this->integer()->notNull(),
            'status' => $this->string(50)->notNull(),
            'stripe_subscription_id' => $this->string(255)->unique(),
            'stripe_customer_id' => $this->string(255),
            'current_period_start' => $this->dateTime(),
            'current_period_end' => $this->dateTime(),
            'canceled_at' => $this->dateTime(),
            'cancel_at_period_end' => $this->boolean()->defaultValue(0),
            'trial_start' => $this->dateTime(),
            'trial_end' => $this->dateTime(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'currency' => $this->string(3)->notNull()->defaultValue('USD'),
            'interval' => $this->string(20)->notNull(),
            'interval_count' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-subscriptions-user_id',
            'subscriptions',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-subscriptions-plan_id',
            'subscriptions',
            'plan_id',
            'subscription_plans',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-subscriptions-user-id', 'subscriptions', 'user_id');
        $this->createIndex('idx-subscriptions-plan-id', 'subscriptions', 'plan_id');
        $this->createIndex('idx-subscriptions-status', 'subscriptions', 'status');
        $this->createIndex('idx-subscriptions-stripe-subscription-id', 'subscriptions', 'stripe_subscription_id');
        $this->createIndex('idx-subscriptions-stripe-customer-id', 'subscriptions', 'stripe_customer_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscriptions-user_id', 'subscriptions');
        $this->dropForeignKey('fk-subscriptions-plan_id', 'subscriptions');
        $this->dropTable('subscriptions');
    }
}
