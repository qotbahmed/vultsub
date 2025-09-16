<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "talentContact".
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $title
 * @property string|null $message
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TalentContact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'talentContact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'required'],
            [['message'], 'string'],
            [['name', 'phone', 'email', 'title', 'created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'phone' => Yii::t('common', 'Phone'),
            'email' => Yii::t('common', 'Email'),
            'title' => Yii::t('common', 'Title'),
            'message' => Yii::t('common', 'Message'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
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
}
