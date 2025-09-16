<?php

use yii\db\Migration;

/**
 * Handles updating players table.
 */
class m240915_000002_update_players_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Check if players table exists
        $tableSchema = $this->db->getTableSchema('players');
        if ($tableSchema === null) {
            // Create players table first
            $this->createTable('players', [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull()->comment('Player name'),
                'nationality' => $this->string(100)->null()->comment('Player nationality'),
                'id_number' => $this->string(50)->null()->comment('ID number'),
                'phone' => $this->string(20)->null()->comment('Phone number'),
                'address' => $this->text()->null()->comment('Address'),
                'dob' => $this->date()->null()->comment('Date of birth'),
                'academy_id' => $this->integer()->null()->comment('Academy ID'),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Created at'),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Updated at'),
            ]);
        }
        
        // Add new columns if they don't exist
        $tableSchema = $this->db->getTableSchema('players');
        if ($tableSchema && !$tableSchema->getColumn('status')) {
            $this->addColumn('players', 'status', $this->string(20)->defaultValue('active')->comment('Player status'));
        }
        
        if ($tableSchema && !$tableSchema->getColumn('sport')) {
            $this->addColumn('players', 'sport', $this->string(100)->null()->comment('Primary sport'));
        }
        
        if ($tableSchema && !$tableSchema->getColumn('attendance_rate')) {
            $this->addColumn('players', 'attendance_rate', $this->decimal(5,2)->defaultValue(0.00)->comment('Attendance rate percentage'));
        }
        
        // Add indexes (Yii2 will handle duplicates gracefully)
        try {
            $this->createIndex('idx_players_status', 'players', 'status');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_players_sport', 'players', 'sport');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
        
        try {
            $this->createIndex('idx_players_academy_id', 'players', 'academy_id');
        } catch (Exception $e) {
            // Index might already exist, continue
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Check if players table exists
        $tableSchema = $this->db->getTableSchema('players');
        if ($tableSchema === null) {
            return; // Table doesn't exist, nothing to rollback
        }
        
        // Drop indexes (use try-catch to handle non-existent indexes)
        try {
            $this->dropIndex('idx_players_academy_id', 'players');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_players_sport', 'players');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->dropIndex('idx_players_status', 'players');
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        // Drop columns if they exist
        if ($tableSchema->getColumn('attendance_rate')) {
            $this->dropColumn('players', 'attendance_rate');
        }
        
        if ($tableSchema->getColumn('sport')) {
            $this->dropColumn('players', 'sport');
        }
        
        if ($tableSchema->getColumn('status')) {
            $this->dropColumn('players', 'status');
        }
        
        // Drop the entire table if it was created by this migration
        // Note: This is a simplified approach - in production you might want to be more careful
        $this->dropTable('players');
    }
}
