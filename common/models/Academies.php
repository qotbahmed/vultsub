<?php

namespace common\models;
use Yii;

use \common\models\base\Academies as BaseAcademies;

/**
 * This is the model class for table "academies".
 * @property Promos[] $promos
 */
class Academies extends BaseAcademies
{

    public function getPromos()
    {
        return $this->hasMany(Promos::class, ['academy_id' => 'id']);
    }
    /**
     * Convert the array of days to a comma-separated string before saving to the database.
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (is_array($this->days)) {
                $this->days = implode(',', $this->days);
            }

            $this->startTime = date('H:i:s', strtotime($this->startTime));
            $this->endTime = date('H:i:s', strtotime($this->endTime));

            return true;
        }
        return false;
    }
    /**
     * Convert the comma-separated string back to an array after retrieving from the database.
     */
    public function afterFind()
    {
        parent::afterFind();
        if (!empty($this->days)) {
            $this->days = explode(',', $this->days);
        }
        if (!empty($this->startTime)) {
            $this->startTime = date('h:i A', strtotime($this->startTime));
        }
        if (!empty($this->endTime)) {
            $this->endTime = date('h:i A', strtotime($this->endTime));
        }
    }

    /**
     * Gets the translated default promos based on current language
     * @return array Default promos with translated names
     */
    private function getDefaultPromos()
    {
        return [
            [Yii::t('academy', 'Sibling Discount', ['en' => 'Sibling Discount', 'ar' => 'خصم الاخوة']), 'percentage', 0, 0, false],
            [Yii::t('academy', 'Two Activities Discount', ['en' => 'Two Activities Discount', 'ar' => 'خصم اشتراك نشاطين']), 'percentage', 0, 0, false],
            [Yii::t('academy', 'Four Activities Discount', ['en' => 'Four Activities Discount', 'ar' => 'خصم ٤ انشطة']), 'percentage', 0, 0, false],
            [Yii::t('academy', 'Six or More Activities Discount', ['en' => 'Six or More Activities Discount', 'ar' => 'خصم ٦ انشطة أو اكثر']), 'percentage', 0, 0, false],
            [Yii::t('academy', 'Manager Discount', ['en' => 'Manager Discount', 'ar' => 'خصم المدير']), 'percentage', 0, 0, false]
        ];
    }

    /**
     * Gets the translated default expense categories based on current language
     * @return array Default expense categories with translated names
     */
    private function getDefaultExpenseCategories()
    {
        return [
            Yii::t('academy', 'Operational Expenses', ['en' => 'Operational Expenses', 'ar' => 'المصروفات التشغيلية']) => [
                [Yii::t('academy', 'Trainer Salaries', ['en' => 'Trainer Salaries', 'ar' => 'رواتب المدربين'])],
                [Yii::t('academy', 'Staff Salaries (Non-Trainers)', ['en' => 'Staff Salaries (Non-Trainers)', 'ar' => 'رواتب موظفين (غير المدربين)'])],
                [Yii::t('academy', 'Rent', ['en' => 'Rent', 'ar' => 'الإيجارات'])],
                [Yii::t('academy', 'Maintenance', ['en' => 'Maintenance', 'ar' => 'الصيانة'])],
                [Yii::t('academy', 'Sports Equipment', ['en' => 'Sports Equipment', 'ar' => 'أدوات ومعدات رياضية'])],
                [Yii::t('academy', 'Utilities', ['en' => 'Utilities', 'ar' => 'تكاليف المياه والكهرباء والتدفئة'])]
            ],
            Yii::t('academy', 'Administrative Expenses', ['en' => 'Administrative Expenses', 'ar' => 'المصروفات الإدارية']) => [
                [Yii::t('academy', 'Management Salaries', ['en' => 'Management Salaries', 'ar' => 'رواتب الإدارة.'])],
                [Yii::t('academy', 'Marketing and Advertising', ['en' => 'Marketing and Advertising', 'ar' => 'تكاليف التسويق والإعلان.'])],
                [Yii::t('academy', 'Office Supplies', ['en' => 'Office Supplies', 'ar' => 'مستلزمات المكتب والقرطاسية'])],
                [Yii::t('academy', 'Consulting Expenses', ['en' => 'Consulting Expenses', 'ar' => 'مصروفات استشارية'])]
            ],
            Yii::t('academy', 'Educational Expenses', ['en' => 'Educational Expenses', 'ar' => 'المصروفات التعليمية']) => [
                [Yii::t('academy', 'Training Materials', ['en' => 'Training Materials', 'ar' => 'المواد التدريبية والمناهج الرياضية.'])],
                [Yii::t('academy', 'Training Program Costs', ['en' => 'Training Program Costs', 'ar' => 'تكاليف البرامج التدريبية'])],
                [Yii::t('academy', 'Workshop Costs', ['en' => 'Workshop Costs', 'ar' => 'تكاليف ورش العمل.'])],
                [Yii::t('academy', 'Training Clothes', ['en' => 'Training Clothes', 'ar' => 'ملابس التدريب'])]
            ],
            Yii::t('academy', 'Seasonal Expenses', ['en' => 'Seasonal Expenses', 'ar' => 'مصروفات موسمية']) => [
                [Yii::t('academy', 'Event Organization Costs', ['en' => 'Event Organization Costs', 'ar' => 'تكاليف تنظيم فعالية'])],
                [Yii::t('academy', 'Awards and Honors', ['en' => 'Awards and Honors', 'ar' => 'جوائز وتكريمات.'])],
                [Yii::t('academy', 'Travel and Accommodation', ['en' => 'Travel and Accommodation', 'ar' => 'تكاليف السفر والإقامة للمشاركين.'])]
            ],
            Yii::t('academy', 'Insurance Expenses', ['en' => 'Insurance Expenses', 'ar' => 'المصروفات التأمينية']) => [
                [Yii::t('academy', 'Facility Insurance', ['en' => 'Facility Insurance', 'ar' => 'تأمين المنشأة الرياضية.'])],
                [Yii::t('academy', 'Staff and Athletes Insurance', ['en' => 'Staff and Athletes Insurance', 'ar' => 'تأمين الموظفين والرياضيين.'])]
            ],
            Yii::t('academy', 'Tax Expenses', ['en' => 'Tax Expenses', 'ar' => 'المصروفات الضريبية']) => [
                [Yii::t('academy', 'Local Taxes', ['en' => 'Local Taxes', 'ar' => 'الضرائب المحلية'])],
                [Yii::t('academy', 'Government Subscriptions', ['en' => 'Government Subscriptions', 'ar' => 'الاشتراكات الحكومية'])]
            ]
        ];
    }

    /**
     * Gets the translated default age groups based on current language
     * @return array Default age groups with translated names
     */
    private function getDefaultAgeGroups()
    {
        return [
            [Yii::t('academy', 'Children (under 6 years)', ['en' => 'Children (under 6 years)', 'ar' => 'أطفال (أقل من 6 سنوات)']), 1, 5],
            [Yii::t('academy', 'Buds', ['en' => 'Buds', 'ar' => 'البراعم']), 6, 8],
            [Yii::t('academy', 'Juniors', ['en' => 'Juniors', 'ar' => 'الناشئين']), 9, 12],
            [Yii::t('academy', 'Cubs', ['en' => 'Cubs', 'ar' => 'الأشبال']), 13, 15],
            [Yii::t('academy', 'Youth', ['en' => 'Youth', 'ar' => 'الناشئين']), 16, 18],
            [Yii::t('academy', 'Young Adults', ['en' => 'Young Adults', 'ar' => 'الشباب']), 19, 25],
            [Yii::t('academy', 'Men', ['en' => 'Men', 'ar' => 'رجال']), null, null],
            [Yii::t('academy', 'Women', ['en' => 'Women', 'ar' => 'سيدات']), null, null]
        ];
    }

public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        foreach (Academies::find()->all() as $academy) {
            $academy->createDefaultBalances();
        }
        
        // Logic for adding default promos when parent_id is not null
        if ($insert && $this->parent_id !== null) {
            $defaultPromos = $this->getDefaultPromos();
    
            foreach ($defaultPromos as $promoData) {
                $promo = new Promos();
                $promo->name = $promoData[0];
                $promo->discount_type = $promoData[1];
                $promo->percentage = $promoData[2];
                $promo->amount = $promoData[3];
                $promo->allow_stack = $promoData[4];
                $promo->academy_id = $this->id;
                $promo->save(false); 
            }
    
          
    
            // Adding default expense categories
            $defaultCategories = $this->getDefaultExpenseCategories();
    
            foreach ($defaultCategories as $mainCategory => $subCategories) {
                $mainCategoryModel = new ManExpensesCategory();
                $mainCategoryModel->title = $mainCategory;
                $mainCategoryModel->academy_id = $this->id;
                $mainCategoryModel->created_at = date('Y-m-d H:i:s');
                $mainCategoryModel->updated_at = date('Y-m-d H:i:s');
                $mainCategoryModel->created_by = \Yii::$app->user->id;
                $mainCategoryModel->updated_by = \Yii::$app->user->id;
                $mainCategoryModel->save(false);
    
                foreach ($subCategories as $subCategory) {
                    $subCategoryModel = new ExpensesCategory();
                    $subCategoryModel->name = $subCategory[0];
                    $subCategoryModel->academy_id = $this->id;
                    $subCategoryModel->main_category_id = $mainCategoryModel->id;
                    $subCategoryModel->created_at = date('Y-m-d H:i:s');
                    $subCategoryModel->updated_at = date('Y-m-d H:i:s');
                    $subCategoryModel->created_by = \Yii::$app->user->id;
                    $subCategoryModel->updated_by = \Yii::$app->user->id;
                    $subCategoryModel->save(false);
                }
            }
          
        }
    
        // Logic for adding default age groups when parent_id is null
        if ($insert && $this->parent_id === null) {
            $defaultAgeGroups = $this->getDefaultAgeGroups();
    
            foreach ($defaultAgeGroups as $ageGroupData) {
                $ageGroup = new AgeGroup();
                $ageGroup->group_name = $ageGroupData[0];
                $ageGroup->from = $ageGroupData[1];
                $ageGroup->to = $ageGroupData[2];
                $ageGroup->academy_id = $this->id;
                $ageGroup->save(false); 
            }
        }


    }
    

    public function getSports()
    {
        return $this->hasMany(Sport::className(), ['id' => 'sport_id'])
            ->viaTable('academy_sport', ['academy_id' => 'id']);
    }

    public function getWorkingDays()
    {
        $dayMap = [
            'sun' => 0,
            'mon' => 1,
            'tue' => 2,
            'wed' => 3,
            'thu' => 4,
            'fri' => 5,
            'sat' => 6,
        ];

        return array_map(fn($day) => $dayMap[$day] ?? null, (array)$this->days);
    }

   

  
    public function getPlayerCount()
{
    return UserProfile::find()
        ->joinWith('user') // Join the User model
        ->leftJoin('subscription', 'subscription.parent_id = user.parent_id') // Left join the subscription table based on parent_id
        ->where([
            'user_profile.academy_id' => $this->id, // Academy for the user
            'user.user_type' => \common\models\User::USER_TYPE_PLAYER // User type is player
        ])
        ->orWhere([
            'subscription.academy_id' => $this->id, // Academy for the subscription matches current academy
        ])
        ->andWhere(['or', ['not', ['subscription.academy_id' => null]], ['user_profile.academy_id' => $this->id]]) // If either the subscription's academy is not null or user_profile's academy matches current academy
        ->distinct('user.id') // Ensure distinct users are counted by their user ID
        ->count();
}

    

    public function getTrainerCount()
    {
        return UserProfile::find()
            ->joinWith('user') // Adjust if your relationship is named differently
            ->where(['user_profile.academy_id' => $this->id, 'user.user_type' => User::USER_TYPE_TRAINER])
            ->count();
    }


    public function getAcademySport()
    {
        return $this->hasMany(AcademySport::class, ['academy_id' => 'id']);
    }
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }
    
    public function getDistrict()
    {
        return $this->hasOne(Districts::class, ['id' => 'district_id']);
    }
    protected function createDefaultBalances()
    {
        FinancialBalance::findOrCreate(
            ['academy_id' => $this->id],
            [
                'cash' => 0.00,
                'bank' => 0.00,
                'total_subscription' => 0.00,
                'total_rent' => 0.00,
                'total_order' => 0.00,
                'expenses_total' => 0.00,
                'created_by' => Yii::$app->user->id ?? null,
                'updated_by' => Yii::$app->user->id ?? null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }
    
}
