<?php

use yii\db\Migration;

/**
 * Handles the creation of table `academies`.
 */
class m250201_000002_create_academies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->createTable('academies', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->comment('Linked to users.id'),
            'name' => $this->string(255)->notNull()->comment('Academy name'),
            'branches_count' => $this->integer()->notNull()->comment('Number of branches'),
            'logo' => $this->string(500)->null()->comment('Academy logo path'),
            'created_at' => $this->integer()->null()->comment('Academy creation (unix ts)'),
            'updated_at' => $this->integer()->null()->comment('Last update (unix ts)'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->createIndex('idx-academies-user_id', 'academies', 'user_id');

        $this->execute('SET FOREIGN_KEY_CHECKS=1');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable('academies');
        $this->execute('SET FOREIGN_KEY_CHECKS=1');
    }
}
