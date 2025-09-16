<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m240101_000001_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'verification_token', $this->string(255)->unique());
        $this->addColumn('user', 'first_name', $this->string(255)->notNull());
        $this->addColumn('user', 'last_name', $this->string(255)->notNull());
        $this->addColumn('user', 'phone', $this->string(255));
        $this->addColumn('user', 'is_trial', $this->boolean()->notNull()->defaultValue(1));
        $this->addColumn('user', 'trial_ends_at', $this->dateTime());
        $this->addColumn('user', 'academy_id', $this->integer());
        $this->addColumn('user', 'subdomain', $this->string(255));
        $this->addColumn('user', 'academy_name', $this->string(255)->notNull());
        $this->addColumn('user', 'branches_count', $this->integer()->notNull()->defaultValue(1));
        $this->addColumn('user', 'subscription_status', $this->string(50)->notNull()->defaultValue('trial'));
        $this->addColumn('user', 'subscription_ends_at', $this->dateTime());
        $this->addColumn('user', 'stripe_customer_id', $this->string(255));
        $this->addColumn('user', 'stripe_subscription_id', $this->string(255));
        $this->addColumn('user', 'plan_id', $this->integer());
        $this->addColumn('user', 'email_verified', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('user', 'email_verified_at', $this->dateTime());

  }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
