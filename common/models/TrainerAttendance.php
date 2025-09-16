<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "trainer_attendance".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $date
 * @property string|null $comments
 * @property float|null $hours
 * @property int|null $academy_id
 *
 * @property Academies $academy
 * @property User $user
 */
class TrainerAttendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trainer_attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'academy_id'], 'integer'],
            [['date'], 'required'],
            [['date'], 'safe'],
            [['comments'], 'string'],
            [['hours'], 'number'],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'date' => Yii::t('backend', 'date'),
            'comments' => Yii::t('common', 'Comments'),
            'hours' => Yii::t('common', 'Hours'),
            'academy_id' => Yii::t('common', 'Academy ID'),
        ];
    }

    /**
     * Gets query for [[Academy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }
    public function getAcademies()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    public function getCoachProfile()
    {
        return $this->hasOne(\common\models\CoachProfile::className(), ['user_id' => 'user_id']);
    }

}
