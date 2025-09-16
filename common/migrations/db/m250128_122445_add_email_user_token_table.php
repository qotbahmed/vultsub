<?php

use yii\db\Migration;

/**
 * Class m250128_122445_add_email_user_token_table
 */
class m250128_122445_add_email_user_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_token', 'otp', $this->string(20)->after('token'));

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
        $this->dropColumn('user_token', 'email');
        $this->dropColumn('user_token', 'otp');
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
