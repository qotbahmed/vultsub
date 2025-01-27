<?php

use yii\db\Migration;

/**
 * Class m241108_184325_add_districts
 */
class m241108_184325_add_districts extends Migration
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
        -- Table structure for districts
        -- ----------------------------
        DROP TABLE IF EXISTS `districts`;
        CREATE TABLE `districts` (
          `id` int NOT NULL AUTO_INCREMENT,
          `city_id` int NOT NULL,
          `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `created_by` int DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `updated_by` int DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `city_id` (`city_id`),
          CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
    
        -- ----------------------------
        -- Insert districts into cities
        -- ----------------------------
        INSERT INTO `districts` (`city_id`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
        (1, 'السلام أول', NOW(), NULL, NOW(), NULL),
        (1, 'السلام ثانٍ', NOW(), NULL, NOW(), NULL),
        (1, 'المرج', NOW(), NULL, NOW(), NULL),
        (1, 'المطرية', NOW(), NULL, NOW(), NULL),
        (1, 'النزهة', NOW(), NULL, NOW(), NULL),
        (1, 'عين شمس', NOW(), NULL, NOW(), NULL),
        (1, 'مدينة نصر شرق', NOW(), NULL, NOW(), NULL),
        (1, 'مدينة نصر غرب', NOW(), NULL, NOW(), NULL),
        (1, 'مصر الجديدة', NOW(), NULL, NOW(), NULL),

        (1, 'الأزبكية', NOW(), NULL, NOW(), NULL),
        (1, 'الموسكي', NOW(), NULL, NOW(), NULL),
        (1, 'الوايلي', NOW(), NULL, NOW(), NULL),
        (1, 'باب الشعرية', NOW(), NULL, NOW(), NULL),
        (1, 'بولاق', NOW(), NULL, NOW(), NULL),
        (1, 'عابدين', NOW(), NULL, NOW(), NULL),
        (1, 'حي غرب', NOW(), NULL, NOW(), NULL),
        (1, 'منشأة ناصر', NOW(), NULL, NOW(), NULL),
        (1, 'وسط البلد', NOW(), NULL, NOW(), NULL),

        (1, '15 مايو', NOW(), NULL, NOW(), NULL),
        (1, 'البساتين', NOW(), NULL, NOW(), NULL),
        (1, 'التبين', NOW(), NULL, NOW(), NULL),
        (1, 'الخليفة', NOW(), NULL, NOW(), NULL),
        (1, 'السيدة زينب', NOW(), NULL, NOW(), NULL),
        (1, 'المعادى', NOW(), NULL, NOW(), NULL),
        (1, 'المعصرة', NOW(), NULL, NOW(), NULL),
        (1, 'المقطم', NOW(), NULL, NOW(), NULL),
        (1, 'حلوان', NOW(), NULL, NOW(), NULL),
        (1, 'دار السلام', NOW(), NULL, NOW(), NULL),
        (1, 'طرة', NOW(), NULL, NOW(), NULL),
        (1, 'مصر القديمة', NOW(), NULL, NOW(), NULL),

        (1, 'الأميرية', NOW(), NULL, NOW(), NULL),
        (1, 'الزاوية الحمراء', NOW(), NULL, NOW(), NULL),
        (1, 'الزيتون', NOW(), NULL, NOW(), NULL),
        (1, 'الساحل', NOW(), NULL, NOW(), NULL),
        (1, 'الشرابية', NOW(), NULL, NOW(), NULL),
        (1, 'حدائق القبة', NOW(), NULL, NOW(), NULL),
        (1, 'روض الفرج', NOW(), NULL, NOW(), NULL),
        (1,'شبرا', NOW(), NULL, NOW(), NULL),
    
        (2, 'الدقي', NOW(), NULL, NOW(), NULL),
        (2, 'العجوزة', NOW(), NULL, NOW(), NULL),
        (2, 'المهندسين', NOW(), NULL, NOW(), NULL),
        (2, 'العمرانية', NOW(), NULL, NOW(), NULL),
        (2, 'بولاق الدكرور', NOW(), NULL, NOW(), NULL),
        (2, 'الوراق', NOW(), NULL, NOW(), NULL),
        (2, 'إمبابة', NOW(), NULL, NOW(), NULL),
        (2, 'وسط البلد - الجيزة', NOW(), NULL, NOW(), NULL),
        (2, 'الهرم', NOW(), NULL, NOW(), NULL),
        (2, 'فيصل', NOW(), NULL, NOW(), NULL),
        (2, 'حدائق الأهرام', NOW(), NULL, NOW(), NULL),
        (2, 'منشأة القناطر', NOW(), NULL, NOW(), NULL),
        (2, 'أوسيم', NOW(), NULL, NOW(), NULL),
        (2, 'كرداسة', NOW(), NULL, NOW(), NULL),
        (2, 'أبو النمرس', NOW(), NULL, NOW(), NULL),
        (2, 'الحوامدية', NOW(), NULL, NOW(), NULL),
        (2, 'البدرشين', NOW(), NULL, NOW(), NULL),
        (2, 'الصف', NOW(), NULL, NOW(), NULL),
        (2, 'أطفيح', NOW(), NULL, NOW(), NULL),

        (2, '6 أكتوبر', NOW(), NULL, NOW(), NULL),
        (2, 'الحي المتميز', NOW(), NULL, NOW(), NULL),
        (2, 'حدائق أكتوبر', NOW(), NULL, NOW(), NULL),
        (2, 'جنوب الواحات البحرية', NOW(), NULL, NOW(), NULL),
        (2, 'الواحات البحرية (الباويطي)', NOW(), NULL, NOW(), NULL),

        (2, 'الشيخ زايد', NOW(), NULL, NOW(), NULL),
    
        (3, 'سموحة', NOW(), NULL, NOW(), NULL),
        (3, 'العجمي', NOW(), NULL, NOW(), NULL),
        (3, 'محرم بك', NOW(), NULL, NOW(), NULL),
        (3, 'الرمل', NOW(), NULL, NOW(), NULL),
        (3, 'برج العرب', NOW(), NULL, NOW(), NULL),
        (3, 'كرموز', NOW(), NULL, NOW(), NULL),
        (3, 'الشاطبي', NOW(), NULL, NOW(), NULL),
        (3, 'المنشية', NOW(), NULL, NOW(), NULL),
        (3, 'الإبراهيمية', NOW(), NULL, NOW(), NULL),
        (3, 'باكوس', NOW(), NULL, NOW(), NULL),
        (3, 'الحضرة', NOW(), NULL, NOW(), NULL),
        (3, 'المندرة', NOW(), NULL, NOW(), NULL),
        (3, 'العطارين', NOW(), NULL, NOW(), NULL),
        (3, 'أبو قير', NOW(), NULL, NOW(), NULL),
        (3, 'مينا البصل', NOW(), NULL, NOW(), NULL),
        (3, 'العامرية', NOW(), NULL, NOW(), NULL),
        (3, 'المكس', NOW(), NULL, NOW(), NULL),
        (3, 'الحضرة الجديدة', NOW(), NULL, NOW(), NULL),
        (3, 'غيط العنب', NOW(), NULL, NOW(), NULL),
        (3, 'سبورتنج', NOW(), NULL, NOW(), NULL),
        (3, 'كامب شيزار', NOW(), NULL, NOW(), NULL),
        (3, 'السيوف', NOW(), NULL, NOW(), NULL),
        (3, 'زيزينيا', NOW(), NULL, NOW(), NULL),
        (3, 'رأس التين', NOW(), NULL, NOW(), NULL),
        (3, 'الدخيلة', NOW(), NULL, NOW(), NULL),
        (3, 'سيدي بشر', NOW(), NULL, NOW(), NULL),
        (3, 'سيدي جابر', NOW(), NULL, NOW(), NULL),
        (3, 'فيكتوريا', NOW(), NULL, NOW(), NULL),
        (3, 'المنتزه', NOW(), NULL, NOW(), NULL),
        (3, 'بولكلي', NOW(), NULL, NOW(), NULL),
        (3, 'جليم', NOW(), NULL, NOW(), NULL),
        (3, 'لوران', NOW(), NULL, NOW(), NULL),
        (3, 'كليوباترا', NOW(), NULL, NOW(), NULL),
        (3, 'المعمورة', NOW(), NULL, NOW(), NULL),
        (3, 'الهانوفيل', NOW(), NULL, NOW(), NULL),
        (4, 'المنصورة', NOW(), NULL, NOW(), NULL),
        (4, 'ميت غمر', NOW(), NULL, NOW(), NULL),
        (4, 'بلقاس', NOW(), NULL, NOW(), NULL),
        (4, 'طلخا', NOW(), NULL, NOW(), NULL),
        (4, 'أجا', NOW(), NULL, NOW(), NULL),
        (4, 'السنبلاوين', NOW(), NULL, NOW(), NULL),
        (4, 'شربين', NOW(), NULL, NOW(), NULL),
        (4, 'دكرنس', NOW(), NULL, NOW(), NULL),
        (4, 'بني عبيد', NOW(), NULL, NOW(), NULL),
        (4, 'المطرية', NOW(), NULL, NOW(), NULL),
        (4, 'الجمالية', NOW(), NULL, NOW(), NULL),
        (4, 'تمي الأمديد', NOW(), NULL, NOW(), NULL),
        (4, 'منية النصر', NOW(), NULL, NOW(), NULL),
        (4, 'الكردي', NOW(), NULL, NOW(), NULL),
        (4, 'محلة دمنة', NOW(), NULL, NOW(), NULL),
        (4, 'نبروه', NOW(), NULL, NOW(), NULL),
        (5, 'الغردقة', NOW(), NULL, NOW(), NULL),
        (5, 'سفاجا', NOW(), NULL, NOW(), NULL),
        (5, 'القصير', NOW(), NULL, NOW(), NULL),
        (5, 'مرسى علم', NOW(), NULL, NOW(), NULL),
        (5, 'الشلاتين', NOW(), NULL, NOW(), NULL),
        (5, 'حلايب', NOW(), NULL, NOW(), NULL),
        (5, 'رأس غارب', NOW(), NULL, NOW(), NULL),

        (6, 'دمنهور', NOW(), NULL, NOW(), NULL),
        (6, 'كفر الدوار', NOW(), NULL, NOW(), NULL),
        (6, 'رشيد', NOW(), NULL, NOW(), NULL),
        (6, 'إدكو', NOW(), NULL, NOW(), NULL),
        (6, 'أبو المطامير', NOW(), NULL, NOW(), NULL),
        (6, 'الدلنجات', NOW(), NULL, NOW(), NULL),
        (6, 'المحمودية', NOW(), NULL, NOW(), NULL),
        (6, 'حوش عيسى', NOW(), NULL, NOW(), NULL),
        (6, 'إيتاي البارود', NOW(), NULL, NOW(), NULL),
        (6, 'كوم حمادة', NOW(), NULL, NOW(), NULL),
        (6, 'بدر', NOW(), NULL, NOW(), NULL),
        (6, 'وادي النطرون', NOW(), NULL, NOW(), NULL),

        (7, 'الفيوم', NOW(), NULL, NOW(), NULL),
        (7, 'إطسا', NOW(), NULL, NOW(), NULL),
        (7, 'سنورس', NOW(), NULL, NOW(), NULL),
        (7, 'طامية', NOW(), NULL, NOW(), NULL),
        (7, 'يوسف الصديق', NOW(), NULL, NOW(), NULL),
        (7, 'أبشواي', NOW(), NULL, NOW(), NULL),

        (8, 'طنطا', NOW(), NULL, NOW(), NULL),
        (8, 'المحلة الكبرى', NOW(), NULL, NOW(), NULL),
        (8, 'كفر الزيات', NOW(), NULL, NOW(), NULL),
        (8, 'زفتى', NOW(), NULL, NOW(), NULL),
        (8, 'بسيون', NOW(), NULL, NOW(), NULL),
        (8, 'قطور', NOW(), NULL, NOW(), NULL),
        (8, 'السنطة', NOW(), NULL, NOW(), NULL),
        (8, 'سمنود', NOW(), NULL, NOW(), NULL),

        (9, 'الإسماعيلية', NOW(), NULL, NOW(), NULL),
        (9, 'القنطرة غرب', NOW(), NULL, NOW(), NULL),
        (9, 'القنطرة شرق', NOW(), NULL, NOW(), NULL),
        (9, 'فايد', NOW(), NULL, NOW(), NULL),
        (9, 'التل الكبير', NOW(), NULL, NOW(), NULL),
        (9, 'أبو صوير', NOW(), NULL, NOW(), NULL),
        (9, 'القصاصين', NOW(), NULL, NOW(), NULL),

        (10, 'شبين الكوم', NOW(), NULL, NOW(), NULL),
        (10, 'السادات', NOW(), NULL, NOW(), NULL),
        (10, 'منوف', NOW(), NULL, NOW(), NULL),
        (10, 'أشمون', NOW(), NULL, NOW(), NULL),
        (10, 'الباجور', NOW(), NULL, NOW(), NULL),
        (10, 'تلا', NOW(), NULL, NOW(), NULL),
        (10, 'بركة السبع', NOW(), NULL, NOW(), NULL),
        (10, 'قويسنا', NOW(), NULL, NOW(), NULL),

        (11, 'المنيا', NOW(), NULL, NOW(), NULL),
        (11, 'ملوي', NOW(), NULL, NOW(), NULL),
        (11, 'بني مزار', NOW(), NULL, NOW(), NULL),
        (11, 'مغاغة', NOW(), NULL, NOW(), NULL),
        (11, 'أبو قرقاص', NOW(), NULL, NOW(), NULL),
        (11, 'مطاي', NOW(), NULL, NOW(), NULL),
        (11, 'سمالوط', NOW(), NULL, NOW(), NULL),
        (11, 'العدوة', NOW(), NULL, NOW(), NULL),
        (11, 'دير مواس', NOW(), NULL, NOW(), NULL),

    
        (12, 'بنها', NOW(), NULL, NOW(), NULL),
        (12, 'قليوب', NOW(), NULL, NOW(), NULL),
        (12, 'شبرا الخيمة', NOW(), NULL, NOW(), NULL),
        (12, 'الخانكة', NOW(), NULL, NOW(), NULL),
        (12, 'طوخ', NOW(), NULL, NOW(), NULL),
        (12, 'كفر شكر', NOW(), NULL, NOW(), NULL),
        (12, 'قها', NOW(), NULL, NOW(), NULL),
        (12, 'العبور', NOW(), NULL, NOW(), NULL),
        (12, 'شبين القناطر', NOW(), NULL, NOW(), NULL),
        (12, 'الخصوص', NOW(), NULL, NOW(), NULL),
        (12, 'ديروط', NOW(), NULL, NOW(), NULL),
        (12, 'باسوس', NOW(), NULL, NOW(), NULL),
        (12, 'الشرابية', NOW(), NULL, NOW(), NULL),
        (12, 'مشتهر', NOW(), NULL, NOW(), NULL),
        (12, 'حوش عيسى', NOW(), NULL, NOW(), NULL),
    
        (13, 'الخارجة', NOW(), NULL, NOW(), NULL),
        (13, 'الداخلة', NOW(), NULL, NOW(), NULL),
        (13, 'الفرافرة', NOW(), NULL, NOW(), NULL),
        (13, 'باريس', NOW(), NULL, NOW(), NULL),
        (13, 'بلاط', NOW(), NULL, NOW(), NULL),

        (14, 'حي السويس', NOW(), NULL, NOW(), NULL),
        (14, 'حي الأربعين', NOW(), NULL, NOW(), NULL),
        (14, 'حي عتاقة', NOW(), NULL, NOW(), NULL),
        (14, 'حي الجناين', NOW(), NULL, NOW(), NULL),
        (14, 'حي فيصل', NOW(), NULL, NOW(), NULL),
        (14, 'حي جواني', NOW(), NULL, NOW(), NULL),
        (14, 'حي السلام', NOW(), NULL, NOW(), NULL),
        (14, 'حي القناطر', NOW(), NULL, NOW(), NULL),
        (14, 'حي الصباح', NOW(), NULL, NOW(), NULL),
        (14, 'حي الهايكستب', NOW(), NULL, NOW(), NULL),
        (15, 'أسوان', NOW(), NULL, NOW(), NULL),
        (15, 'كوم أمبو', NOW(), NULL, NOW(), NULL),
        (15, 'دراو', NOW(), NULL, NOW(), NULL),
        (15, 'إدفو', NOW(), NULL, NOW(), NULL),
        (15, 'نصر النوبة', NOW(), NULL, NOW(), NULL),
        (15, 'كلابشة', NOW(), NULL, NOW(), NULL),
        (15, 'البصيلية', NOW(), NULL, NOW(), NULL),
        (15, 'السباعية', NOW(), NULL, NOW(), NULL),
        (15, 'أبو سمبل السياحية', NOW(), NULL, NOW(), NULL),
        (16, 'أسيوط', NOW(), NULL, NOW(), NULL),
        (16, 'ديروط', NOW(), NULL, NOW(), NULL),
        (16, 'منفلوط', NOW(), NULL, NOW(), NULL),
        (16, 'أبنوب', NOW(), NULL, NOW(), NULL),
        (16, 'البداري', NOW(), NULL, NOW(), NULL),
        (16, 'الغنايم', NOW(), NULL, NOW(), NULL),
        (16, 'صدفا', NOW(), NULL, NOW(), NULL),
        (16, 'القوصية', NOW(), NULL, NOW(), NULL),
        (16, 'ساحل سليم', NOW(), NULL, NOW(), NULL),

        (17, 'بني سويف', NOW(), NULL, NOW(), NULL),
        (17, 'إهناسيا', NOW(), NULL, NOW(), NULL),
        (17, 'الفشن', NOW(), NULL, NOW(), NULL),
        (17, 'سمسطا', NOW(), NULL, NOW(), NULL),
        (17, 'ببا', NOW(), NULL, NOW(), NULL),
        (17, 'الواسطى', NOW(), NULL, NOW(), NULL),
        (17, 'ناصر', NOW(), NULL, NOW(), NULL),
        (17, 'بني سويف الجديدة', NOW(), NULL, NOW(), NULL),


        (18, 'الزهور', NOW(), NULL, NOW(), NULL),
        (18, 'المناخ', NOW(), NULL, NOW(), NULL),
        (18, 'الشرق', NOW(), NULL, NOW(), NULL),
        (18, 'العرب', NOW(), NULL, NOW(), NULL),
        (18, 'الجنوب', NOW(), NULL, NOW(), NULL),
        (18, 'الضواحى', NOW(), NULL, NOW(), NULL),
        (18, 'مدينة بورفؤاد', NOW(), NULL, NOW(), NULL),
        (18, 'حى غرب', NOW(), NULL, NOW(), NULL),


        (19, 'دمياط', NOW(), NULL, NOW(), NULL),
        (19, 'فارسكور', NOW(), NULL, NOW(), NULL),
        (19, 'الزرقا', NOW(), NULL, NOW(), NULL),
        (19, 'كفر سعد', NOW(), NULL, NOW(), NULL),
        (19, 'كفر البطيخ', NOW(), NULL, NOW(), NULL),
        (19, 'رأس البر', NOW(), NULL, NOW(), NULL),
        (19, 'الروضة', NOW(), NULL, NOW(), NULL),
        (19, 'التمديدة', NOW(), NULL, NOW(), NULL),
        (19, 'شطا', NOW(), NULL, NOW(), NULL),
        (19, 'عزبة البرج', NOW(), NULL, NOW(), NULL),
        (19, 'السرو', NOW(), NULL, NOW(), NULL),
        (19, 'دمياط الجديدة', NOW(), NULL, NOW(), NULL),
        (20, 'الزقازيق', NOW(), NULL, NOW(), NULL),
        (20, 'بلبيس', NOW(), NULL, NOW(), NULL),
        (20, 'أبو كبير', NOW(), NULL, NOW(), NULL),
        (20, 'فاقوس', NOW(), NULL, NOW(), NULL),
        (20, 'منيا القمح', NOW(), NULL, NOW(), NULL),
        (20, 'أبو حماد', NOW(), NULL, NOW(), NULL),
        (20, 'القرين', NOW(), NULL, NOW(), NULL),
        (20, 'ههيا', NOW(), NULL, NOW(), NULL),
        (20, 'الصالحية الجديدة', NOW(), NULL, NOW(), NULL),
        (20, 'الإبراهيمية', NOW(), NULL, NOW(), NULL),
        (20, 'كفر صقر', NOW(), NULL, NOW(), NULL),
        (20, 'مشتول السوق', NOW(), NULL, NOW(), NULL),
        (20, 'الحسينية', NOW(), NULL, NOW(), NULL),
        (21, 'شرم الشيخ', NOW(), NULL, NOW(), NULL),
        (21, 'دهب', NOW(), NULL, NOW(), NULL),
        (21, 'رأس سدر', NOW(), NULL, NOW(), NULL),
        (21, 'نويبع', NOW(), NULL, NOW(), NULL),
        (21, 'سانت كاترين', NOW(), NULL, NOW(), NULL),
        (21, 'الطور', NOW(), NULL, NOW(), NULL),
        (21, 'أبو رديس', NOW(), NULL, NOW(), NULL),
        (21, 'أبو زنيمة', NOW(), NULL, NOW(), NULL),
        (21, 'طابا', NOW(), NULL, NOW(), NULL),
        (22, 'كفر الشيخ', NOW(), NULL, NOW(), NULL),
        (22, 'دسوق', NOW(), NULL, NOW(), NULL),
        (22, 'بيلا', NOW(), NULL, NOW(), NULL),
        (22, 'الحامول', NOW(), NULL, NOW(), NULL),
        (22, 'سيدي سالم', NOW(), NULL, NOW(), NULL),
        (22, 'مطوبس', NOW(), NULL, NOW(), NULL),
        (22, 'قلين', NOW(), NULL, NOW(), NULL),
        (22, 'سيدي غازي', NOW(), NULL, NOW(), NULL),
        (22, 'الرياض', NOW(), NULL, NOW(), NULL),

        (23, 'مرسى مطروح', NOW(), NULL, NOW(), NULL),
        (23, 'الضبعة', NOW(), NULL, NOW(), NULL),
        (23, 'الحمام', NOW(), NULL, NOW(), NULL),
        (23, 'سيدي براني', NOW(), NULL, NOW(), NULL),
        (23, 'النجيلة', NOW(), NULL, NOW(), NULL),
        (23, 'الساحل الشمالي', NOW(), NULL, NOW(), NULL),
        (23, 'برانيس', NOW(), NULL, NOW(), NULL),
        (23, 'سيوة', NOW(), NULL, NOW(), NULL),
        (23, 'السلوم', NOW(), NULL, NOW(), NULL),
        (24, 'الأقصر', NOW(), NULL, NOW(), NULL),
        (24, 'إسنا', NOW(), NULL, NOW(), NULL),
        (24, 'أرمنت', NOW(), NULL, NOW(), NULL),
        (24, 'البياضية', NOW(), NULL, NOW(), NULL),
        (24, 'الزينية', NOW(), NULL, NOW(), NULL),
        (24, 'الطود', NOW(), NULL, NOW(), NULL),
        (24, 'القرنة', NOW(), NULL, NOW(), NULL),
        (24, 'العديسات', NOW(), NULL, NOW(), NULL),
        (25, 'قنا', NOW(), NULL, NOW(), NULL),
        (25, 'نجع حمادي', NOW(), NULL, NOW(), NULL),
        (25, 'قوص', NOW(), NULL, NOW(), NULL),
        (25, 'قفط', NOW(), NULL, NOW(), NULL),
        (25, 'دشنا', NOW(), NULL, NOW(), NULL),
        (25, 'فرشوط', NOW(), NULL, NOW(), NULL),
        (25, 'الوقف', NOW(), NULL, NOW(), NULL),
        (25, 'أبوتشت', NOW(), NULL, NOW(), NULL),
        (25, 'نقادة', NOW(), NULL, NOW(), NULL),
        (26, 'العريش', NOW(), NULL, NOW(), NULL),
        (26, 'الشيخ زويد', NOW(), NULL, NOW(), NULL),
        (26, 'رفح', NOW(), NULL, NOW(), NULL),
        (26, 'بئر العبد', NOW(), NULL, NOW(), NULL),
        (26, 'الحسنة', NOW(), NULL, NOW(), NULL),
        (26, 'نخل', NOW(), NULL, NOW(), NULL),
        (25, 'قنا', NOW(), NULL, NOW(), NULL),
        (25, 'نجع حمادي', NOW(), NULL, NOW(), NULL),
        (25, 'قوص', NOW(), NULL, NOW(), NULL),
        (25, 'قفط', NOW(), NULL, NOW(), NULL),
        (25, 'دشنا', NOW(), NULL, NOW(), NULL),
        (25, 'فرشوط', NOW(), NULL, NOW(), NULL),
        (25, 'الوقف', NOW(), NULL, NOW(), NULL),
        (25, 'أبوتشت', NOW(), NULL, NOW(), NULL),
        (25, 'نقادة', NOW(), NULL, NOW(), NULL),

        (27, 'سوهاج', NOW(), NULL, NOW(), NULL),
        (27, 'أخميم', NOW(), NULL, NOW(), NULL),
        (27, 'جرجا', NOW(), NULL, NOW(), NULL),
        (27, 'طما', NOW(), NULL, NOW(), NULL),
        (27, 'البلينا', NOW(), NULL, NOW(), NULL),
        (27, 'طهطا', NOW(), NULL, NOW(), NULL),
        (27, 'المراغة', NOW(), NULL, NOW(), NULL),
        (27, 'المنشاة', NOW(), NULL, NOW(), NULL),
        (27, 'دار السلام', NOW(), NULL, NOW(), NULL),
        (27, 'جهينة', NOW(), NULL, NOW(), NULL),
        (27, 'ساقلته', NOW(), NULL, NOW(), NULL);
        SET FOREIGN_KEY_CHECKS = 1;
    ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241108_184325_add_districts cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241108_184325_add_districts cannot be reverted.\n";

        return false;
    }
    */
}
