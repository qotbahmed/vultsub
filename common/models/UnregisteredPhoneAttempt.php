<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "unregistered_phone_attempt".
 *
 * @property int    $id
 * @property string $mobile
 * @property int    $attempts
 * @property string $created_at
 * @property string $updated_at
 */
class UnregisteredPhoneAttempt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%unregistered_phone_attempt}}';
    }

    /**
     * Automatically fill `created_at` and `updated_at` with the current timestamp.
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile'], 'required'],
            [['attempts'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['mobile'], 'string', 'max' => 20],
            [['mobile'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('common', 'ID'),
            'mobile'     => Yii::t('common', 'Mobile Number'),
            'attempts'   => Yii::t('common', 'Attempts'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

 


    /**
     * Finds existing record by mobile or creates a new one.
     *
     * @param string $mobile
     * @return static
     */
    public static function findOrCreate($mobile)
    {
        $model = static::findOne(['mobile' => $mobile]);
        if (!$model) {
            $model = new static([
                'mobile'   => $mobile,
                'attempts' => 0,
            ]);
        }
        return $model;
    }

    /**
     * Record one more attempt for the given mobile.
     *
     * @param string $mobile
     * @return bool
     */
    public static function recordAttempt(string $mobile): bool
    {
        $model = static::findOrCreate($mobile);
        return $model->incrementAttempts(1);
    }
      /**
     * Increment the `attempts` counter,
     * and choose how to save based on whether it's a new record.
     *
     * @param int $by
     * @return bool
     */
    public function incrementAttempts(int $by = 1): bool
    {
        $this->attempts += $by;

        if ($this->isNewRecord) {
            return $this->save(false);
        }

        return $this->save(false, ['attempts', 'updated_at']);
    }
        public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model with optional validation and attribute selection.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
}
