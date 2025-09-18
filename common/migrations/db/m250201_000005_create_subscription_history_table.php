<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscription_history`.
 */
class m250201_000005_create_subscription_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // If an old version exists, drop it first to align with new schema
        $table = $this->db->getTableSchema('subscription_history', true);
        if ($table !== null) {
            $this->execute('SET FOREIGN_KEY_CHECKS=0');
            // Best effort: drop table directly; this will also remove FKs
            $this->dropTable('subscription_history');
            $this->execute('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->createTable('subscription_history', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->comment('Linked to users.id'),
            'subscription_id' => $this->integer()->notNull()->comment('Linked to subscriptions.id'),
            'action' => $this->string(20)->notNull()->comment('Action type: created|activated|expired|renewed|cancelled'),
            'old_status' => $this->string(50)->null()->comment('Previous status'),
            'new_status' => $this->string(50)->notNull()->comment('New status'),
            'notes' => $this->text()->null()->comment('Action notes'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Action timestamp'),
        ]);

  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = $this->db->getTableSchema('subscription_history', true);
        if ($table !== null) {
            $this->dropTable('subscription_history');
        }
    }
}