<?php

use yii\db\Migration;

/**
 * Class m250128_122457_update__settings_table
 */
class m250128_122457_update__settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('settings', 'points_earned_per_riyal', $this->integer()->defaultValue(1));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('settings', 'points_earned_per_riyal');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250128_122445_add_email_user_token_table..php cannot be reverted.\n";

        return false;
    }
    */
}
