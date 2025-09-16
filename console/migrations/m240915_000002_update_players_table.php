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
        // Add new columns if they don't exist
        if (!$this->db->getTableSchema('players')->getColumn('status')) {
            $this->addColumn('players', 'status', $this->enum(['active', 'inactive', 'suspended'])->defaultValue('active')->comment('Player status'));
        }
        
        if (!$this->db->getTableSchema('players')->getColumn('sport')) {
            $this->addColumn('players', 'sport', $this->string(100)->null()->comment('Primary sport'));
        }
        
        if (!$this->db->getTableSchema('players')->getColumn('attendance_rate')) {
            $this->addColumn('players', 'attendance_rate', $this->decimal(5,2)->defaultValue(0.00)->comment('Attendance rate percentage'));
        }
        
        // Add indexes
        $this->createIndex('idx_players_status', 'players', 'status');
        $this->createIndex('idx_players_sport', 'players', 'sport');
        $this->createIndex('idx_players_academy_id', 'players', 'academy_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_players_academy_id', 'players');
        $this->dropIndex('idx_players_sport', 'players');
        $this->dropIndex('idx_players_status', 'players');
        
        $this->dropColumn('players', 'attendance_rate');
        $this->dropColumn('players', 'sport');
        $this->dropColumn('players', 'status');
    }
}
