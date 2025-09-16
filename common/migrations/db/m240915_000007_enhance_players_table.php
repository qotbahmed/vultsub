<?php

use yii\db\Migration;

/**
 * Handles adding additional columns to table `players`.
 * This migration is now redundant as the columns are added in m240915_000002_update_players_table.php
 */
class m240915_000007_enhance_players_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // This migration is now redundant - columns are added in m240915_000002_update_players_table.php
        // Skip this migration as the work is already done in m240915_000002_update_players_table.php
        echo "Skipping m240915_000007_enhance_players_table - columns already added in m240915_000002_update_players_table.php\n";
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // This migration is now redundant - no rollback needed
        echo "Skipping rollback for m240915_000007_enhance_players_table - no changes made\n";
        return true;
    }
}
