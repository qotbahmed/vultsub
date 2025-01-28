<?php

use yii\db\Migration;

/**
 * Class m250128_122442_add_progress_logs_to_profile_table
 */
class m250128_122442_add_progress_logs_to_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_profile}}', 'surah', $this->char());
        $this->addColumn('{{%user_profile}}', 'ayah_num', $this->integer()->defaultValue(0));
        $this->addColumn('{{%user_profile}}', 'points_num', $this->integer()->defaultValue(0));
        $this->addColumn('{{%user_profile}}', 'page_num', $this->integer()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250128_122442_add_progress_logs_to_profile_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250128_122442_add_progress_logs_to_profile_table cannot be reverted.\n";

        return false;
    }
    */
}
