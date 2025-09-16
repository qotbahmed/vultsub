<?php

use yii\db\Migration;

/**
 * Handles adding subscription and trial columns to table `academies`.
 */
class m240915_000005_enhance_academies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Check if academies table exists
        $tableSchema = $this->db->getTableSchema('academies');
        if ($tableSchema === null) {
            echo "academies table doesn't exist, skipping enhancement\n";
            return true;
        }
        
        // Add Vult integration column if it doesn't exist
        if (!$tableSchema || !$tableSchema->getColumn('vult_request_id')) {
            $this->addColumn('academies', 'vult_request_id', $this->integer()->null()->comment('Vult request ID'));
        }
        
        // Add subscription management columns if they don't exist
        if (!$tableSchema || !$tableSchema->getColumn('subscription_plan')) {
            $this->addColumn('academies', 'subscription_plan', $this->string(50)->defaultValue('trial')->comment('Subscription plan'));
        }
        
        if (!$tableSchema || !$tableSchema->getColumn('subscription_status')) {
            $this->addColumn('academies', 'subscription_status', $this->string(50)->defaultValue('active')->comment('Subscription status'));
        }
        
        if (!$tableSchema || !$tableSchema->getColumn('subscription_start')) {
            $this->addColumn('academies', 'subscription_start', $this->dateTime()->null()->comment('Subscription start date'));
        }
        
        if (!$tableSchema || !$tableSchema->getColumn('subscription_end')) {
            $this->addColumn('academies', 'subscription_end', $this->dateTime()->null()->comment('Subscription end date'));
        }
        
        // Add trial management columns if they don't exist
        if (!$tableSchema || !$tableSchema->getColumn('trial_start')) {
            $this->addColumn('academies', 'trial_start', $this->integer()->null()->comment('Trial start timestamp'));
        }
        
        if (!$tableSchema || !$tableSchema->getColumn('trial_end')) {
            $this->addColumn('academies', 'trial_end', $this->integer()->null()->comment('Trial end timestamp'));
        }
        
        if (!$tableSchema || !$tableSchema->getColumn('trial_status')) {
            $this->addColumn('academies', 'trial_status', $this->string(50)->defaultValue('active')->comment('Trial status'));
        }
        
        // Add revenue tracking column if it doesn't exist
        if (!$tableSchema || !$tableSchema->getColumn('monthly_revenue')) {
            $this->addColumn('academies', 'monthly_revenue', $this->decimal(10, 2)->defaultValue(0.00)->comment('Monthly revenue'));
        }
        
        // Create indexes for new columns (use try-catch to handle duplicates)
        try {
            $this->createIndex('idx_academies_vult_request_id', 'academies', 'vult_request_id');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_academies_subscription_plan', 'academies', 'subscription_plan');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_academies_subscription_status', 'academies', 'subscription_status');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_academies_trial_status', 'academies', 'trial_status');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Check if academies table exists
        $tableSchema = $this->db->getTableSchema('academies');
        if ($tableSchema === null) {
            return; // Table doesn't exist, nothing to rollback
        }
        
        // Drop indexes (use try-catch to handle non-existent indexes)
        try {
            $this->dropIndex('idx_academies_trial_status', 'academies');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_academies_subscription_status', 'academies');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_academies_subscription_plan', 'academies');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_academies_vult_request_id', 'academies');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        // Drop columns if they exist
        if ($tableSchema->getColumn('monthly_revenue')) {
            $this->dropColumn('academies', 'monthly_revenue');
        }
        
        if ($tableSchema->getColumn('trial_status')) {
            $this->dropColumn('academies', 'trial_status');
        }
        
        if ($tableSchema->getColumn('trial_end')) {
            $this->dropColumn('academies', 'trial_end');
        }
        
        if ($tableSchema->getColumn('trial_start')) {
            $this->dropColumn('academies', 'trial_start');
        }
        
        if ($tableSchema->getColumn('subscription_end')) {
            $this->dropColumn('academies', 'subscription_end');
        }
        
        if ($tableSchema->getColumn('subscription_start')) {
            $this->dropColumn('academies', 'subscription_start');
        }
        
        if ($tableSchema->getColumn('subscription_status')) {
            $this->dropColumn('academies', 'subscription_status');
        }
        
        if ($tableSchema->getColumn('subscription_plan')) {
            $this->dropColumn('academies', 'subscription_plan');
        }
        
        if ($tableSchema->getColumn('vult_request_id')) {
            $this->dropColumn('academies', 'vult_request_id');
        }
    }
}
