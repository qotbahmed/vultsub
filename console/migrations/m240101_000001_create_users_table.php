<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m240101_000001_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'verification_token' => $this->string(255)->unique(),
            'auth_key' => $this->string(255)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(5), // 5 = trial
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'first_name' => $this->string(255)->notNull(),
            'last_name' => $this->string(255)->notNull(),
            'phone' => $this->string(255),
            'is_trial' => $this->boolean()->notNull()->defaultValue(1),
            'trial_ends_at' => $this->dateTime(),
            'academy_id' => $this->integer(),
            'subdomain' => $this->string(255)->notNull()->unique(),
            'academy_name' => $this->string(255)->notNull(),
            'branches_count' => $this->integer()->notNull()->defaultValue(1),
            'subscription_status' => $this->string(50)->notNull()->defaultValue('trial'),
            'subscription_ends_at' => $this->dateTime(),
            'stripe_customer_id' => $this->string(255),
            'stripe_subscription_id' => $this->string(255),
            'plan_id' => $this->integer(),
            'email_verified' => $this->boolean()->notNull()->defaultValue(0),
            'email_verified_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx-users-email', 'users', 'email');
        $this->createIndex('idx-users-subdomain', 'users', 'subdomain');
        $this->createIndex('idx-users-status', 'users', 'status');
        $this->createIndex('idx-users-subscription-status', 'users', 'subscription_status');
        $this->createIndex('idx-users-stripe-customer-id', 'users', 'stripe_customer_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
