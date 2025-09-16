<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription_details".
 *
 * @property int $id
 * @property int $player_id
 * @property int $packages_id
 * @property int $sports_id
 * @property int $subscription_id
 * @property string $start_date
 * @property string $end_date
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $classes
 * @property string $package_name
 * @property int $amount
 * @property string $sport_name
 * @property string|null $suspend_comment
 * @property string|null $from_date
 * @property string|null $to_date
 * @property int $is_suspended
 * @property int|null $coach_id
 *
 * @property Package $packages
 * @property Sport $sports
 * @property Subscription $subscription
 * @property CoachProfile $coach
 */
class SubscriptionDetails extends \yii\db\ActiveRecord
{
    /**
     * Returns the dynamic end date for the subscription detail based on package type and remaining classes.
     * - If package is by duration only: returns end_date.
     * - If package is by classes only: returns null if classes remain, or end_date if finished.
     * - If package is by both: returns end_date if classes finished or duration finished, whichever comes first.
     * @return string|null
     */
    public function getDynamicEndDate()
    {
        if (!$this->packages) return $this->end_date;
        $package = $this->packages;
        $attended = $this->getAttendedClassesCount();
        $remaining = max(0, (int)$this->classes - $attended);
        $now = date('Y-m-d');
        // By duration only
        if ($package->package_type == \common\models\Package::PACKAGE_TYPE_DURATION_ONLY) {
            return $this->end_date;
        }
        // By classes only
        if ($package->package_type == \common\models\Package::PACKAGE_TYPE_CLASSES_ONLY) {
            return $remaining > 0 ? null : $this->end_date;
        }
        // By both: ends at earliest of (end_date, when classes run out)
        if ($package->package_type == \common\models\Package::PACKAGE_TYPE_CLASSES_AND_DURATION) {
            if ($remaining > 0 && $now <= $this->end_date) {
                return null; // Still active
            } else {
                return $this->end_date;
            }
        }
        return $this->end_date;
    }

    /**
     * Returns the number of remaining classes for this subscription detail.
     * @return int
     */
    public function getRemainingClasses()
    {
        $attended = $this->getAttendedClassesCount();
        return max(0, (int)$this->classes - $attended);
    }

    /**
     * Returns the number of attended classes for this subscription detail.
     * @return int
     */
    public function getAttendedClassesCount()
    {
        return \common\models\PlayerAttendance::find()
            ->where(['sub_details_id' => $this->id])
            ->count();
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['player_id', 'packages_id', 'sports_id', 'subscription_id', 'package_name', 'amount', 'sport_name', 'start_date', 'end_date'], 'required'],
            [['player_id', 'packages_id', 'sports_id', 'subscription_id', 'created_by', 'updated_by', 'classes', 'amount', 'is_suspended', 'coach_id'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at', 'from_date', 'to_date'], 'safe'],
            [['package_name', 'sport_name'], 'string', 'max' => 255],
            [['packages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::class, 'targetAttribute' => ['packages_id' => 'id']],
            [['subscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::class, 'targetAttribute' => ['subscription_id' => 'id']],
            [['sports_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::class, 'targetAttribute' => ['sports_id' => 'id']],
            [['coach_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoachProfile::class, 'targetAttribute' => ['coach_id' => 'id']],
            [['suspend_comment'], 'string'],

        ];
    }
    public function validateStartDate($attribute, $params)
    {
        if ($this->$attribute < date('Y-m-d')) {
            $this->addError($attribute, 'تاريخ بداية الاشتراك لا يمكن أن يكون في الماضي.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'player_id' => Yii::t('common', 'Player ID'),
            'packages_id' => Yii::t('common', 'Packages ID'),
            'sports_id' => Yii::t('common', 'Sports ID'),
            'subscription_id' => Yii::t('common', 'Subscription ID'),
            'start_date' => Yii::t('common', 'Start Date'),
            'end_date' => Yii::t('common', 'End Date'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'classes' => Yii::t('common', 'Classes'),
            'package_name' => Yii::t('common', 'Package Name'),
            'amount' => Yii::t('common', 'Amount'),
            'sport_name' => Yii::t('common', 'Sport Name'),
            'parent_id' => Yii::t('common', 'Parent ID'),
            'suspend_comment' => Yii::t('common', 'Suspend Comment'),
            'from_date' => Yii::t('common', 'From Date'),
            'to_date' => Yii::t('common', 'To Date'),
            'is_suspended' => Yii::t('common', 'Is Suspended'),
            'coach_id' => Yii::t('common', 'Coach'),

        ];
    }

    /**
     * Retrieve the package details and set the classes and amount properties.
     */
    public function getPackageDetails()
    {
        // Retrieve the package using the relationship
        $package = $this->getPackage()->one();

        if ($package) {
            // Always set classes to 0 if null
            $this->classes = ($package->classes !== null) ? $package->classes : 0;
            $this->amount = $package->amount;
            $this->package_name = $package->name;
            $this->sport_name = $package->sport->title;
        } else {
            // Handle the case where the package is null
            $this->classes = 0;
            $this->amount = 0;
            $this->package_name = '';
            $this->sport_name = '';
            // Optionally, you can throw an exception or just set defaults
            // throw new \Exception('Package not found for the provided package ID.');
        }
    }


    /**
     * Gets query for [[Subscription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::class, ['id' => 'subscription_id']);
    }

    public function loadAll($data)
    {
        return $this->load($data);
    }

    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Add additional deletion logic here, if necessary.
            if ($this->delete() === false) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function getSportName()
    {
        return $this->sports ? $this->sports->title : null;
    }
    public function getPlayer()
    {
        return $this->hasOne(User::className(), ['id' => 'player_id']);
    }

    public function getPlayerName()
    {
        return $this->hasOne(User::class, ['id' => 'player_id'])
            ->via('userProfile');
    }
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'player_id']);
    }

    public function getUser()
    {
        $userProfile = $this->getUserProfile()->one();
        if ($userProfile) {
            return $userProfile->firstname ?: $userProfile->user->username;
        } else {
            $user = User::findOne($this->player_id);
            return $user ? $user->username : 'Unknown';
        }
    }

    public function getPlayerFirstName()
    {
        return $this->userProfile ? $this->userProfile->firstname : Yii::t('common', 'Unknown');
    }

    public function getSports()
    {
        return $this->hasOne(Sport::class, ['id' => 'sports_id']);
    }

    public function getPackages()
    {
        return $this->hasOne(Package::class, ['id' => 'packages_id']);
    }

    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'packages_id']);
    }

    // Define the relationship with Sport
    public function getSport()
    {
        return $this->hasOne(Sport::className(), ['id' => 'sports_id']);
    }


    public function getSchedules()
    {
        return $this->hasMany(Schedules::class, ['academy_sport_id' => 'sport_id']);
    }
    public function getSchedule()
    {
        return $this->hasOne(Schedules::className(), ['id' => 'subscription_id']);
    }

    public function getCoachName()
    {
        if ($this->coach && $this->coach->user && $this->coach->user->userProfile) {
            return $this->coach->user->userProfile->firstname . ' ' . $this->coach->user->userProfile->lastname;
        }
        return Yii::t('common', 'No Coach Assigned');
    }

    public function getCoach()
    {
        return $this->hasOne(CoachProfile::class, ['id' => 'coach_id']);
    }
    public function getAcademyId()
{
    return $this->subscription->academy_id ?? null;
}

  
}
