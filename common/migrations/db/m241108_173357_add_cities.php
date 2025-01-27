<?php

use yii\db\Migration;

/**
 * Class m241108_173357_add_cities
 */
class m241108_173357_add_cities extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        SET NAMES utf8mb4;
        SET FOREIGN_KEY_CHECKS = 0;

        -- ----------------------------
        -- Table structure for cities
        -- ----------------------------
        DROP TABLE IF EXISTS `cities`;
        CREATE TABLE `cities` (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `created_by` int DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `updated_by` int DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

        -- ----------------------------
        -- Insert governorates into cities
        -- ----------------------------
        INSERT INTO `cities` (`name`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
        ('القاهرة', NOW(), NULL, NOW(), NULL),
        ('الجيزة', NOW(), NULL, NOW(), NULL),
        ('الإسكندرية', NOW(), NULL, NOW(), NULL),
        ('الدقهلية', NOW(), NULL, NOW(), NULL),
        ('البحر الأحمر', NOW(), NULL, NOW(), NULL),
        ('البحيرة', NOW(), NULL, NOW(), NULL),
        ('الفيوم', NOW(), NULL, NOW(), NULL),
        ('الغربية', NOW(), NULL, NOW(), NULL),
        ('الإسماعيلية', NOW(), NULL, NOW(), NULL),
        ('المنوفية', NOW(), NULL, NOW(), NULL),
        ('المنيا', NOW(), NULL, NOW(), NULL),
        ('القليوبية', NOW(), NULL, NOW(), NULL),
        ('الوادي الجديد', NOW(), NULL, NOW(), NULL),
        ('السويس', NOW(), NULL, NOW(), NULL),
        ('أسوان', NOW(), NULL, NOW(), NULL),
        ('أسيوط', NOW(), NULL, NOW(), NULL),
        ('بني سويف', NOW(), NULL, NOW(), NULL),
        ('بورسعيد', NOW(), NULL, NOW(), NULL),
        ('دمياط', NOW(), NULL, NOW(), NULL),
        ('الشرقية', NOW(), NULL, NOW(), NULL),
        ('جنوب سيناء', NOW(), NULL, NOW(), NULL),
        ('كفر الشيخ', NOW(), NULL, NOW(), NULL),
        ('مطروح', NOW(), NULL, NOW(), NULL),
        ('الأقصر', NOW(), NULL, NOW(), NULL),
        ('قنا', NOW(), NULL, NOW(), NULL),
        ('شمال سيناء', NOW(), NULL, NOW(), NULL),
        ('سوهاج', NOW(), NULL, NOW(), NULL);

        SET FOREIGN_KEY_CHECKS = 1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241108_173357_add_cities cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241108_173357_add_cities cannot be reverted.\n";

        return false;
    }
    */
}
