<?php

use yii\db\Migration;

/**
 * Class m241113_221653_addCustomRole
 */
class m241113_221653_addCustomRole extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        INSERT INTO `rbac_auth_item` (`name`, `type`, `description`) VALUES ('customRole', 1, 'customRole');
INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('customRole', 'loginToBackend');
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241113_221653_addCustomRole cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241113_221653_addCustomRole cannot be reverted.\n";

        return false;
    }
    */
}
