<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscription_plans`.
 */
class m240101_000002_create_subscription_plans_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscription_plans', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'branches_limit' => $this->integer()->notNull(),
            'students_limit' => $this->integer()->notNull(),
            'storage_limit_mb' => $this->integer()->notNull(),
            'price_monthly' => $this->decimal(10, 2)->notNull(),
            'price_yearly' => $this->decimal(10, 2)->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(1),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'stripe_price_id_monthly' => $this->string(255),
            'stripe_price_id_yearly' => $this->string(255),
            'features' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-subscription-plans-active', 'subscription_plans', 'is_active');
        $this->createIndex('idx-subscription-plans-sort-order', 'subscription_plans', 'sort_order');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscription_plans');
    }
}
