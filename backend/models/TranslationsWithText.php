<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "translations_with_text".
 *
 * @property int $id
 * @property string $table_name
 * @property int $model_id
 * @property string $attribute
 * @property string $lang
 * @property string $value
 */
class TranslationsWithText extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'translations_with_text';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['table_name', 'model_id', 'attribute', 'lang', 'value'], 'required'],
            [['model_id'], 'integer'],
            [['value'], 'string'],
            [['table_name', 'attribute'], 'string', 'max' => 100],
            [['lang'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table_name' => 'Table Name',
            'model_id' => 'Model ID',
            'attribute' => 'Attribute',
            'lang' => 'Lang',
            'value' => 'Value',
        ];
    }
}
