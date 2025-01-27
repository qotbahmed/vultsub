<?php

use yii\db\Migration;

/**
 * Class m220320_203914_initProject
 */
class m220320_203914_initProject extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql=file_get_contents(__DIR__ . '/sql/intialize.sql');
        $this->execute($sql);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220320_203914_initProject cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220320_203914_initProject cannot be reverted.\n";

        return false;
    }
    */
}
