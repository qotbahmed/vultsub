<?php

use yii\db\Migration;

/**
 * Handles the creation of table `academies`.
 */
class m240915_000000_create_academies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('academies', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'slug' => $this->string(255)->null(),
            'title' => $this->string(100)->notNull(),
            'contact_phone' => $this->string(25)->null(),
            'description' => $this->text()->null(),
            'contact_email' => $this->string(255)->null(),
            'logo_path' => $this->string(255)->null(),
            'logo_base_url' => $this->string(255)->null(),
            'address' => $this->string(255)->null(),
            'location' => $this->string(255)->null(),
            'city_id' => $this->integer()->null(),
            'district_id' => $this->integer()->null(),
            'lat' => $this->string(255)->null(),
            'lng' => $this->string(255)->null(),
            'manager_id' => $this->integer()->null(),
            'parent_id' => $this->bigInteger()->unsigned()->null(),
            'main' => $this->boolean()->null(),
            'created_by' => $this->bigInteger()->null(),
            'updated_by' => $this->bigInteger()->null(),
            'created_at' => $this->string(255)->null(),
            'updated_at' => $this->string(255)->null(),
            'days' => $this->string(255)->null(),
            'startTime' => $this->time()->null(),
            'endTime' => $this->time()->null(),
            'extraPhone' => $this->string(255)->null(),
            'district' => $this->string(255)->null(),
            'primary_color' => $this->string(255)->null(),
            'secondary_color' => $this->string(255)->null(),
            'sport_icons' => $this->text()->null(),
            'tax_number' => $this->string(255)->null(),
            'commercial_registration_number' => $this->string(255)->null(),
            'password' => $this->string(255)->null(),
            'add_tax' => $this->decimal(10, 2)->defaultValue(15.00),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'qr_login_path' => $this->string(255)->null(),
            'qr_login_base_url' => $this->string(255)->null(),
            'qr_link' => $this->string(255)->null(),
            'qr_path' => $this->string(255)->null(),
            'qr_base_url' => $this->string(255)->null(),
            'complete_profile' => $this->tinyInteger()->defaultValue(0),
            'bio' => $this->text()->null()->comment('Biography of the academy'),
            'facebook' => $this->string(255)->null()->comment('Facebook URL'),
            'youtube' => $this->string(255)->null()->comment('YouTube URL'),
            'twitter' => $this->string(255)->null()->comment('Twitter URL'),
            'instagram' => $this->string(255)->null()->comment('Instagram URL'),
            'linkedin' => $this->string(255)->null()->comment('LinkedIn URL'),
            'whatsapp' => $this->string(255)->null()->comment('WhatsApp Number'),
            'total_rating' => $this->float()->defaultValue(0),
            'count_rate' => $this->integer()->defaultValue(0),
            'stamp_image' => $this->string(255)->null(),
            'stamp_logo_base_url' => $this->string(255)->null(),
            'signature_image' => $this->string(255)->null(),
            'signature_logo_base_url' => $this->string(255)->null(),
            'mobile_notifications_enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('0: disabled mobile messages, 1: enabled'),
            'report_notification_phone' => $this->string(20)->null(),
            'qr_unified_link' => $this->text()->null(),
            'qr_unified_path' => $this->string(500)->null(),
            'qr_unified_base_url' => $this->string(500)->null(),
        ]);

        // Add indexes
        $this->createIndex('parent_id', 'academies', 'parent_id');
        $this->createIndex('fk-academies-city_id', 'academies', 'city_id');
        $this->createIndex('fk-academies-district_id', 'academies', 'district_id');

        // Add foreign key constraints (only if referenced tables exist)
        try {
            $this->addForeignKey('academies_ibfk_1', 'academies', 'parent_id', 'academies', 'id');
        } catch (Exception $e) {
            // Foreign key might not be possible to add, continue
        }

        try {
            $this->addForeignKey('fk-academies-city_id', 'academies', 'city_id', 'cities', 'id', 'SET NULL', 'CASCADE');
        } catch (Exception $e) {
            // Foreign key might not be possible to add, continue
        }

        try {
            $this->addForeignKey('fk-academies-district_id', 'academies', 'district_id', 'districts', 'id', 'SET NULL', 'CASCADE');
        } catch (Exception $e) {
            // Foreign key might not be possible to add, continue
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign keys first
        try {
            $this->dropForeignKey('fk-academies-district_id', 'academies');
        } catch (Exception $e) {
            // Foreign key might not exist, continue
        }

        try {
            $this->dropForeignKey('fk-academies-city_id', 'academies');
        } catch (Exception $e) {
            // Foreign key might not exist, continue
        }

        try {
            $this->dropForeignKey('academies_ibfk_1', 'academies');
        } catch (Exception $e) {
            // Foreign key might not exist, continue
        }

        // Drop indexes
        $this->dropIndex('fk-academies-district_id', 'academies');
        $this->dropIndex('fk-academies-city_id', 'academies');
        $this->dropIndex('parent_id', 'academies');

        // Drop table
        $this->dropTable('academies');
    }
}
