<?php

use yii\db\Migration;

/**
 * Handles adding additional columns to table `academy_requests`.
 */
class m240915_000004_enhance_academy_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Check if academy_requests table exists
        $tableSchema = $this->db->getTableSchema('academy_requests');
        if ($tableSchema === null) {
            echo "academy_requests table doesn't exist, skipping enhancement\n";
            return true;
        }
        
        // Add portal integration columns if they don't exist
        if (!$tableSchema->getColumn('portal_academy_id')) {
            $this->addColumn('academy_requests', 'portal_academy_id', $this->integer()->null()->comment('Portal academy ID'));
        }
        
        if (!$tableSchema->getColumn('portal_user_id')) {
            $this->addColumn('academy_requests', 'portal_user_id', $this->integer()->null()->comment('Portal user ID'));
        }
        
        if (!$tableSchema->getColumn('user_id')) {
            $this->addColumn('academy_requests', 'user_id', $this->integer()->null()->comment('User ID who created the request'));
        }
        
        // Note: approved_at and rejected_at columns already exist in the base table
        // No need to add them again
        
        // Create indexes for new columns (use try-catch to handle duplicates)
        try {
            $this->createIndex('idx_academy_requests_portal_academy_id', 'academy_requests', 'portal_academy_id');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_academy_requests_portal_user_id', 'academy_requests', 'portal_user_id');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_academy_requests_approved_at', 'academy_requests', 'approved_at');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_academy_requests_rejected_at', 'academy_requests', 'rejected_at');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_academy_requests_user_id', 'academy_requests', 'user_id');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Check if academy_requests table exists
        $tableSchema = $this->db->getTableSchema('academy_requests');
        if ($tableSchema === null) {
            return; // Table doesn't exist, nothing to rollback
        }
        
        // Drop indexes (use try-catch to handle non-existent indexes)
        try {
            $this->dropIndex('idx_academy_requests_user_id', 'academy_requests');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_academy_requests_rejected_at', 'academy_requests');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_academy_requests_approved_at', 'academy_requests');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_academy_requests_portal_user_id', 'academy_requests');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_academy_requests_portal_academy_id', 'academy_requests');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        // Drop columns if they exist (only the ones added by this migration)
        if ($tableSchema->getColumn('user_id')) {
            $this->dropColumn('academy_requests', 'user_id');
        }
        
        if ($tableSchema->getColumn('portal_user_id')) {
            $this->dropColumn('academy_requests', 'portal_user_id');
        }
        
        if ($tableSchema->getColumn('portal_academy_id')) {
            $this->dropColumn('academy_requests', 'portal_academy_id');
        }
        
        // Note: approved_at and rejected_at are part of the base table, don't drop them
    }
}
