<?php

use yii\db\Migration;

/**
 * Handles the creation of support tables.
 */
class m250201_000007_create_support_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create support_tickets table
        $this->createTable('support_tickets', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->comment('Linked to users.id'),
            'subject' => $this->string(255)->notNull()->comment('Ticket subject'),
            'description' => $this->text()->notNull()->comment('Ticket description'),
            'priority' => $this->string(20)->notNull()->comment('Ticket priority: low|medium|high|urgent'),
            'status' => $this->string(20)->notNull()->comment('Ticket status: open|in_progress|resolved|closed'),
            'assigned_to' => $this->integer()->null()->comment('Assigned support agent'),
            'resolution' => $this->text()->null()->comment('Resolution notes'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ticket creation'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'resolved_at' => $this->timestamp()->null()->comment('Resolution timestamp'),
        ]);

        // Create support_messages table
        $this->createTable('support_messages', [
            'id' => $this->primaryKey()->unsigned(),
            'ticket_id' => $this->integer()->notNull()->comment('Linked to support_tickets.id'),
            'sender_id' => $this->integer()->notNull()->comment('Message sender'),
            'sender_type' => $this->string(20)->notNull()->comment('Sender type: user|support'),
            'message' => $this->text()->notNull()->comment('Message content'),
            'attachments' => $this->json()->null()->comment('File attachments'),
            'is_read' => $this->boolean()->notNull()->defaultValue(false)->comment('Read status'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Message creation'),
        ]);

        // Create renewal_notifications table
        $this->createTable('renewal_notifications', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->comment('Linked to users.id'),
            'subscription_id' => $this->integer()->notNull()->comment('Linked to subscriptions.id'),
            'notification_type' => $this->string(30)->notNull()->comment('Notification type: renewal_reminder|payment_failed|renewal_success'),
            'days_before' => $this->integer()->null()->comment('Days before renewal'),
            'sent_at' => $this->timestamp()->null()->comment('Sent timestamp'),
            'status' => $this->string(20)->notNull()->comment('Notification status: pending|sent|failed'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Notification creation'),
        ]);

        // Add foreign keys
      

   

  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('renewal_notifications');
        $this->dropTable('support_messages');
        $this->dropTable('support_tickets');
    }
}