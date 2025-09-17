<?php

use yii\db\Migration;

/**
 * Handles adding missing columns to the `players` table.
 */
class m250117_000001_add_missing_columns_to_players_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Check if players table exists
        $tableSchema = $this->db->getTableSchema('players');
        if ($tableSchema === null) {
            echo "Players table does not exist. Please run the previous migration first.\n";
            return false;
        }
        
        // Add email column if it doesn't exist
        if (!$tableSchema->getColumn('email')) {
            $this->addColumn('players', 'email', $this->string(255)->null()->comment('Player email'));
            echo "Added email column to players table.\n";
        }
        
        // Add date_of_birth column if it doesn't exist
        if (!$tableSchema->getColumn('date_of_birth')) {
            $this->addColumn('players', 'date_of_birth', $this->date()->null()->comment('Date of birth'));
            echo "Added date_of_birth column to players table.\n";
        }
        
        // Add sport column if it doesn't exist
        if (!$tableSchema->getColumn('sport')) {
            $this->addColumn('players', 'sport', $this->string(100)->null()->comment('Primary sport'));
            echo "Added sport column to players table.\n";
        }
        
        // Add level column if it doesn't exist
        if (!$tableSchema->getColumn('level')) {
            $this->addColumn('players', 'level', $this->string(50)->defaultValue('beginner')->comment('Player level'));
            echo "Added level column to players table.\n";
        }
        
        // Add status column if it doesn't exist
        if (!$tableSchema->getColumn('status')) {
            $this->addColumn('players', 'status', $this->string(20)->defaultValue('active')->comment('Player status'));
            echo "Added status column to players table.\n";
        }
        
        // Add indexes for better performance
        try {
            $this->createIndex('idx_players_email', 'players', 'email');
            echo "Created index on email column.\n";
        } catch (Exception $e) {
            echo "Index on email column might already exist.\n";
        }
        
        try {
            $this->createIndex('idx_players_status', 'players', 'status');
            echo "Created index on status column.\n";
        } catch (Exception $e) {
            echo "Index on status column might already exist.\n";
        }
        
        try {
            $this->createIndex('idx_players_sport', 'players', 'sport');
            echo "Created index on sport column.\n";
        } catch (Exception $e) {
            echo "Index on sport column might already exist.\n";
        }
        
        try {
            $this->createIndex('idx_players_academy_id', 'players', 'academy_id');
            echo "Created index on academy_id column.\n";
        } catch (Exception $e) {
            echo "Index on academy_id column might already exist.\n";
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
            echo "Players table does not exist. Nothing to rollback.\n";
            return true;
        }
        
        // Drop indexes first
        try {
            $this->dropIndex('idx_players_academy_id', 'players');
            echo "Dropped index on academy_id column.\n";
        } catch (Exception $e) {
            echo "Index on academy_id column might not exist.\n";
        }
        
        try {
            $this->dropIndex('idx_players_sport', 'players');
            echo "Dropped index on sport column.\n";
        } catch (Exception $e) {
            echo "Index on sport column might not exist.\n";
        }
        
        try {
            $this->dropIndex('idx_players_status', 'players');
            echo "Dropped index on status column.\n";
        } catch (Exception $e) {
            echo "Index on status column might not exist.\n";
        }
        
        try {
            $this->dropIndex('idx_players_email', 'players');
            echo "Dropped index on email column.\n";
        } catch (Exception $e) {
            echo "Index on email column might not exist.\n";
        }
        
        // Drop columns in reverse order
        if ($tableSchema->getColumn('status')) {
            $this->dropColumn('players', 'status');
            echo "Dropped status column.\n";
        }
        
        if ($tableSchema->getColumn('level')) {
            $this->dropColumn('players', 'level');
            echo "Dropped level column.\n";
        }
        
        if ($tableSchema->getColumn('sport')) {
            $this->dropColumn('players', 'sport');
            echo "Dropped sport column.\n";
        }
        
        if ($tableSchema->getColumn('date_of_birth')) {
            $this->dropColumn('players', 'date_of_birth');
            echo "Dropped date_of_birth column.\n";
        }
        
        if ($tableSchema->getColumn('email')) {
            $this->dropColumn('players', 'email');
            echo "Dropped email column.\n";
        }
    }
}
