<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use prokhonenkov\yii2repeater\RepeaterWidget;
use webvimark\behaviors\multilanguage\MultiLanguageHelper;



/**
 * This is the model class for table "promos".
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_en
 * @property int $amount
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $academy_id
 * @property int|null $percentage
 * @property string|null $discount_type
 * @property int|null $allow_stack
 * @property Academies $academy

 */
class Promos extends ActiveRecord
{
    public $promos = [0];
    const PROMO_TYPE_AMOUNT = 'amount';
    const PROMO_TYPE_PERCENTAGE = 'percentage';
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 0;
    const STATUS_BY_ADMIN = 2;
    
    // No longer needed - using database field name_en instead

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%promos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'academy_id', 'discount_type'], 'required'],
            [['percentage'], 'required', 'when' => function($model) {
                return $model->discount_type === self::PROMO_TYPE_AMOUNT;
            }, 'whenClient' => "function (attribute, value) {
                return $('#discount-type').val() === ".self::PROMO_TYPE_AMOUNT.";
            }"],
            [['amount'], 'required', 'when' => function($model) {
                return $model->discount_type === 'amount';
            }, 'whenClient' => "function (attribute, value) {
                return $('#discount-type').val() === 'amount';
            }"],
            [['percentage'], 'number', 'max' => 100, 'min' => 0],
            [['allow_stack'], 'integer'],
            [['name', 'name_en'], 'string', 'max' => 255],
            [['discount_type'], 'in', 'range' => ['percentage', 'amount']],
            ['promos', 'safe'],

            [['name'], 'unique', 'targetAttribute' => ['name', 'academy_id'], 'message' => Yii::t('common', 'This name has already been taken for this academy.')],
            ['amount', 'number', 'min' => 0, 'message' => Yii::t('common', 'Price must be a positive number.')],
            





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
            'name_en' => Yii::t('common', 'English Name'),
            'amount' => Yii::t('common', 'Amount'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'academy_id' => Yii::t('common', 'Academy'),
            'percentage' => Yii::t('common', 'Percentage'),
            'discount_type' => Yii::t('common', 'Discount Type'),
            'allow_stack' => Yii::t('common', 'Allow Stacking with Other Discounts'),
            'status' => Yii::t('common', 'Status'),

        ];
    }

 
    public function getAcademy()
    {
        return $this->hasOne(Academies::className(), ['id' => 'academy_id']);
    }
    
    /**
     * Get the translated name based on current language after finding the record
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // Use the English name if the current language is English and the name_en field is not empty
        $currentLang = Yii::$app->language;
        if ($currentLang === 'en' && !empty($this->name_en)) {
            $this->name = $this->name_en;
        }
        // For Arabic (or any other language), we keep using the default 'name' field
    }
    public function beforeValidate()
    {
        // if (parent::beforeValidate()) {
        //     if (Yii::$app->user->identity && Yii::$app->user->identity->userProfile && Yii::$app->user->identity->userProfile->academy_id) {
        //         $this->academy_id = Yii::$app->user->identity->userProfile->academy_id;
        //     }
        if (parent::beforeValidate()) {
            // Only set academy_id if it is not set manually
            if ($this->academy_id === null && Yii::$app->user->identity && Yii::$app->user->identity->userProfile && Yii::$app->user->identity->userProfile->academy_id) {
                $this->academy_id = Yii::$app->user->identity->userProfile->academy_id;
            }
            return true;
        }
        return false;
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

public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->academy->parent_id === null) {
                $this->addError('academy_id', 'لا يمكن تطبيق الخصومات على الأكاديميات الرئيسية.');
                return false;
            }
            if ($this->discount_type === 'percentage') {
                $this->amount = 0;
            } elseif ($this->discount_type === 'amount') {
                $this->percentage = 0;
            }
            return true;
        }
        return false;
    }

    public function loadAll($data)
{
    $this->load($data);
    
    if (isset($data['Promos']['promos']) && is_array($data['Promos']['promos'])) {
        $this->promos = $data['Promos']['promos'];
    }
    
    Yii::debug('Loaded promos data: ' . print_r($this->promos, true));
    
    return $this;
}

public function saveAll($runValidation = true, $attributeNames = null)
{
    $transaction = Yii::$app->db->beginTransaction();
    try {
        if (!$this->save($runValidation, $attributeNames)) {
            Yii::error('Failed to save main model: ' . print_r($this->errors, true));
            $transaction->rollBack();
            return false;
        }

        if (!is_array($this->promos)) {
            $this->promos = [];
        }

        foreach ($this->promos as $promoData) {
            $promoModel = new self();
            $promoModel->attributes = $promoData;
            $promoModel->academy_id = $this->academy_id;

            if (!$promoModel->save($runValidation)) {
                Yii::error('Failed to save promo model: ' . print_r($promoModel->errors, true));
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    } catch (\Exception $e) {
        Yii::error("Error saving promos: " . $e->getMessage());
        $transaction->rollBack();
        throw $e;
    }
}

public function promoStatuses()
{
    return [
        self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">'.Yii::t('backend', 'Not Active').'</span>',
        self::STATUS_ACTIVE => '<span class="status-slot btn-primary">'.Yii::t('backend', 'Active').'</span>',
        self::STATUS_BY_ADMIN => '<span class="status-slot btn-warning">'.Yii::t('backend', 'By Admin').'</span>',
    ];
}
public static function getStatuses($controllerType)
{
    $statuses = [
        self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
        self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        self::STATUS_BY_ADMIN => Yii::t('backend', 'By Admin')
    ];


    return $statuses;
} 
              
}
