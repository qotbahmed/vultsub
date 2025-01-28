<?php

use yii\db\Migration;

/**
 * Class m250124_193247_edittoken
 */
class m250128_122445_add_email_user_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        
        ALTER TABLE user_token
ADD COLUMN `email` varchar(255) NULL AFTER `otp`;
        ");



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
