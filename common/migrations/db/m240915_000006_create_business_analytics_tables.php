<?php

use yii\db\Migration;

/**
 * Handles the creation of business analytics tables.
 */
class m240915_000006_create_business_analytics_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create business_analytics table
        $this->createTable('business_analytics', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull()->comment('Analytics date'),
            'total_requests' => $this->integer()->defaultValue(0)->comment('Total requests count'),
            'approved_requests' => $this->integer()->defaultValue(0)->comment('Approved requests count'),
            'total_academies' => $this->integer()->defaultValue(0)->comment('Total academies count'),
            'active_academies' => $this->integer()->defaultValue(0)->comment('Active academies count'),
            'trial_academies' => $this->integer()->defaultValue(0)->comment('Trial academies count'),
            'basic_academies' => $this->integer()->defaultValue(0)->comment('Basic plan academies count'),
            'premium_academies' => $this->integer()->defaultValue(0)->comment('Premium plan academies count'),
            'enterprise_academies' => $this->integer()->defaultValue(0)->comment('Enterprise plan academies count'),
            'monthly_revenue' => $this->decimal(10, 2)->defaultValue(0.00)->comment('Monthly revenue'),
            'conversion_rate' => $this->decimal(5, 2)->defaultValue(0.00)->comment('Conversion rate'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created timestamp'),
        ]);

        // Create unique index for date
        $this->createIndex('idx_business_analytics_date', 'business_analytics', 'date', true);

        // Create subscription_history table
        $this->createTable('subscription_history', [
            'id' => $this->primaryKey(),
            'academy_id' => $this->bigInteger()->unsigned()->notNull()->comment('Academy ID'),
            'old_plan' => $this->string(50)->null()->comment('Previous subscription plan'),
            'new_plan' => $this->string(50)->notNull()->comment('New subscription plan'),
            'old_status' => $this->string(50)->null()->comment('Previous subscription status'),
            'new_status' => $this->string(50)->notNull()->comment('New subscription status'),
            'changed_by' => $this->integer()->null()->comment('User who made the change'),
            'change_reason' => $this->text()->null()->comment('Reason for change'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created timestamp'),
        ]);

        // Create indexes for subscription_history
        $this->createIndex('idx_subscription_history_academy_id', 'subscription_history', 'academy_id');
        $this->createIndex('idx_subscription_history_created_at', 'subscription_history', 'created_at');

        // Create trial_events table
        $this->createTable('trial_events', [
            'id' => $this->primaryKey(),
            'academy_id' => $this->bigInteger()->unsigned()->notNull()->comment('Academy ID'),
            'user_id' => $this->integer()->notNull()->comment('User ID'),
            'event_type' => $this->string(50)->notNull()->comment('Event type'),
            'event_data' => $this->text()->null()->comment('Event data (JSON)'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created timestamp'),
        ]);

        // Create indexes for trial_events
        $this->createIndex('idx_trial_events_academy_id', 'trial_events', 'academy_id');
        $this->createIndex('idx_trial_events_user_id', 'trial_events', 'user_id');
        $this->createIndex('idx_trial_events_event_type', 'trial_events', 'event_type');

        // Add foreign key constraints (use try-catch for safety)
        try {
            $this->addForeignKey(
                'fk_subscription_history_academy_id',
                'subscription_history',
                'academy_id',
                'academies',
                'id',
                'CASCADE',
                'CASCADE'
            );
        } catch (Exception $e) {
            // Foreign key might not be possible to add, continue
        }

        try {
            $this->addForeignKey(
                'fk_subscription_history_changed_by',
                'subscription_history',
                'changed_by',
                'user',
                'id',
                'SET NULL',
                'CASCADE'
            );
        } catch (Exception $e) {
            // Foreign key might not be possible to add, continue
        }

        try {
            $this->addForeignKey(
                'fk_trial_events_academy_id',
                'trial_events',
                'academy_id',
                'academies',
                'id',
                'CASCADE',
                'CASCADE'
            );
        } catch (Exception $e) {
            // Foreign key might not be possible to add, continue
        }

        try {
            $this->addForeignKey(
                'fk_trial_events_user_id',
                'trial_events',
                'user_id',
                'user',
                'id',
                'CASCADE',
                'CASCADE'
            );
        } catch (Exception $e) {
            // Foreign key might not be possible to add, continue
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign keys (use try-catch for safety)
        try {
            $this->dropForeignKey('fk_trial_events_user_id', 'trial_events');
        } catch (Exception $e) {
            // Foreign key might not exist, continue
        }

        try {
            $this->dropForeignKey('fk_trial_events_academy_id', 'trial_events');
        } catch (Exception $e) {
            // Foreign key might not exist, continue
        }

        try {
            $this->dropForeignKey('fk_subscription_history_changed_by', 'subscription_history');
        } catch (Exception $e) {
            // Foreign key might not exist, continue
        }

        try {
            $this->dropForeignKey('fk_subscription_history_academy_id', 'subscription_history');
        } catch (Exception $e) {
            // Foreign key might not exist, continue
        }

        // Drop indexes
        $this->dropIndex('idx_trial_events_event_type', 'trial_events');
        $this->dropIndex('idx_trial_events_user_id', 'trial_events');
        $this->dropIndex('idx_trial_events_academy_id', 'trial_events');
        $this->dropIndex('idx_subscription_history_created_at', 'subscription_history');
        $this->dropIndex('idx_subscription_history_academy_id', 'subscription_history');
        $this->dropIndex('idx_business_analytics_date', 'business_analytics');

        // Drop tables
        $this->dropTable('trial_events');
        $this->dropTable('subscription_history');
        $this->dropTable('business_analytics');
    }
}
