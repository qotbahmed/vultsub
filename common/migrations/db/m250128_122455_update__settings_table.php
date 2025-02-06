<?php

use yii\db\Migration;

/**
 * Class m250124_193247_edittoken
 */
class m250128_122455_update__settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('settings', 'points_per_second', $this->integer());
        $this->addColumn('settings', 'reading_points_delay', $this->integer());
        $this->addColumn('settings', 'max_daily_points_per_user', $this->integer());
        $this->addColumn('settings', 'daily_points', $this->integer());





    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250128_122445_add_email_user_token_table..php cannot be reverted.\n";

        return false;
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
