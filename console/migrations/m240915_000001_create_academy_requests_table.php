<?php

use yii\db\Migration;

/**
 * Handles the creation of table `academy_requests`.
 */
class m240915_000001_create_academy_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('academy_requests', [
            'id' => $this->primaryKey(),
            'academy_name' => $this->string(255)->notNull()->comment('Academy name'),
            'manager_name' => $this->string(255)->notNull()->comment('Manager name'),
            'email' => $this->string(255)->notNull()->comment('Email address'),
            'phone' => $this->string(20)->notNull()->comment('Phone number'),
            'address' => $this->text()->null()->comment('Address'),
            'city' => $this->string(100)->null()->comment('City'),
            'branches_count' => $this->integer()->defaultValue(1)->comment('Number of branches'),
            'sports' => $this->text()->null()->comment('Sports offered'),
            'description' => $this->text()->null()->comment('Description'),
            'status' => $this->enum(['pending', 'approved', 'rejected'])->defaultValue('pending')->comment('Request status'),
            'requested_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Request timestamp'),
            'approved_at' => $this->timestamp()->null()->comment('Approval timestamp'),
            'rejected_at' => $this->timestamp()->null()->comment('Rejection timestamp'),
            'notes' => $this->text()->null()->comment('Admin notes'),
            'created_by' => $this->integer()->null()->comment('Created by user ID'),
            'updated_by' => $this->integer()->null()->comment('Updated by user ID'),
        ]);

        // Add indexes
        $this->createIndex('idx_academy_requests_status', 'academy_requests', 'status');
        $this->createIndex('idx_academy_requests_requested_at', 'academy_requests', 'requested_at');
        $this->createIndex('idx_academy_requests_email', 'academy_requests', 'email');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('academy_requests');
    }
}
