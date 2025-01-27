<?php

use yii\db\Migration;

/**
 * Class m241111_165729_modifyauthitem
 */
class m241111_165729_modifyauthitem extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `rbac_auth_item` 
ADD COLUMN `assignment_category` int NULL AFTER `updated_at`;");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241111_165729_modifyauthitem cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241111_165729_modifyauthitem cannot be reverted.\n";

        return false;
    }
    */
}
