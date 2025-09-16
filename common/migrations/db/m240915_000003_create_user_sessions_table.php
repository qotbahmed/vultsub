<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_sessions`.
 */
class m240915_000003_create_user_sessions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_sessions', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('User ID'),
            'token' => $this->string(64)->notNull()->comment('Session token'),
            'target_database' => $this->string(20)->notNull()->comment('Target database'),
            'created_at' => $this->integer()->notNull()->comment('Created timestamp'),
            'expires_at' => $this->integer()->notNull()->comment('Expires timestamp'),
        ]);

        // Create indexes
        $this->createIndex('idx_user_sessions_token', 'user_sessions', 'token', true);
        $this->createIndex('idx_user_sessions_user_id', 'user_sessions', 'user_id');
        $this->createIndex('idx_user_sessions_expires_at', 'user_sessions', 'expires_at');

        // Add foreign key constraint
        $this->addForeignKey(
            'fk_user_sessions_user_id',
            'user_sessions',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key
        $this->dropForeignKey('fk_user_sessions_user_id', 'user_sessions');

        // Drop indexes
        $this->dropIndex('idx_user_sessions_expires_at', 'user_sessions');
        $this->dropIndex('idx_user_sessions_user_id', 'user_sessions');
        $this->dropIndex('idx_user_sessions_token', 'user_sessions');

        // Drop table
        $this->dropTable('user_sessions');
    }
}
