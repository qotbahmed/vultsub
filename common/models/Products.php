<?php

namespace common\models;
use trntv\filekit\behaviors\UploadBehavior;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $title
 * @property int $academy_id
 * @property int $sport_id
 * @property int $category_id
 * @property int $quantity
 * @property int $quantity_used
 * @property int $quantity_remaining
 * @property float $price
 * @property string|null $logo_base_url
 * @property string|null $logo_path
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $status
 * @property Academies $academy
 * @property ProductsCategory $category
 * @property OrderDetails[] $orderDetails
 * @property AcademySport $sport
 */
class Products extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    public $image;
    use RelationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'academy_id', 'sport_id', 'category_id', 'quantity', 'quantity_used', 'quantity_remaining', 'price'], 'required'],
            [['academy_id', 'sport_id', 'category_id', 'quantity', 'quantity_used', 'quantity_remaining', 'created_by', 'updated_by'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at','image'], 'safe'],
            [['title', 'logo_base_url', 'logo_path'], 'string', 'max' => 255],
          //  ['title', 'match', 'pattern' => '/^[a-zA-Z][a-zA-Z0-9\s]{2,}$/', 'message' => Yii::t('common', 'Title must start with a letter, contain at least 3 characters, and contain only letters, numbers, and spaces.')],
            ['title', 'match', 'pattern' => '/^[\p{L}][\p{L}0-9\s]{2,}$/u', 'message' => Yii::t('common', 'Title must start with a letter, contain at least 3 characters, and contain only letters, numbers, and spaces.')],
            ['title', 'string', 'min' => 3, 'max' => 100, 'tooShort' => Yii::t('common', 'Title should be at least 3 characters.'), 'tooLong' => Yii::t('common', 'Title should not exceed 100 characters.')],
            ['quantity', 'integer', 'min' => 1, 'message' => Yii::t('common', 'Quantity must be a non-negative integer.')],
            ['quantity', 'number', 'min' => 1, 'message' => Yii::t('common', 'Quantity must be a positive integer.')],
            [['description'], 'match', 'pattern' => '/^[^\s].*/u', 'message' => Yii::t('common', 'Description cannot start with a space.')],
            ['description', 'string', 'min' => 5, 'max' => 10000, 'tooShort' => Yii::t('common', 'Description should be at least 10 characters.'), 'tooLong' => Yii::t('common', 'Description should not exceed 1000 characters.')],
            ['price', 'number', 'min' => 1,'max' => 100000, 'message' => Yii::t('common', 'Price must be a positive number.')],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademySport::class, 'targetAttribute' => ['sport_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsCategory::class, 'targetAttribute' => ['category_id' => 'id']],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'sport_id' => Yii::t('common', 'Sport ID'),
            'category_id' => Yii::t('common', 'Category ID'),
            'quantity' => Yii::t('common', 'Quantity'),
            'quantity_used' => Yii::t('common', 'Quantity Used'),
            'quantity_remaining' => Yii::t('common', 'Quantity Remaining'),
            'price' => Yii::t('common', 'Price Piece'),
            'logo_base_url' => Yii::t('common', 'Logo Base Url'),
            'logo_path' => Yii::t('common', 'Logo Path'),
            'description' => Yii::t('common', 'Description'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'logo_path',
                'baseUrlAttribute' => 'logo_base_url'
            ],
        ];
    }
    
 
    /**
     * Gets query for [[Academy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademies()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductsCategory::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[OrderDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetails::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Sport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(AcademySport::class, ['id' => 'sport_id']);
    }
    public function getProductsCategory()
{
    return $this->hasOne(ProductsCategory::class, ['id' => 'category_id']);
}

    public function getAcademySport()
{
    return $this->hasOne(AcademySport::class, ['id' => 'sport_id']);
}


public function getLogo($default = '')
{
    return $this->logo_path
        ? rtrim(Yii::getAlias($this->logo_base_url), '/') . '/' . ltrim(Yii::getAlias($this->logo_path), '/')
        : $default;
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
    public  function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }
    public function productsStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">'.Yii::t('backend', 'Not Active').'</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">'.Yii::t('backend', 'Active').'</span>'
        ];
    }
    public static function getStatuses($controllerType)
    {
        $statuses = [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];

    
        return $statuses;
    }
}
