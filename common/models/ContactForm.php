<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contact_form".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $subject
 * @property string $inquiry
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class ContactForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'subject', 'inquiry'], 'required'],
            [['inquiry'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['name', 'email', 'subject'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
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
            'email' => Yii::t('common', 'Email'),
            'phone' => Yii::t('common', 'Phone'),
            'subject' => Yii::t('common', 'Subject'),
            'inquiry' => Yii::t('common', 'Inquiry'),
            'created_at' => Yii::t('common', 'Created At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'updated_by' => Yii::t('common', 'Updated By'),
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
