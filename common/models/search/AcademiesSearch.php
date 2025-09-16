<?php

namespace common\models\search;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Academies;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;

/**
 * common\models\search\AcademiesSearch represents the model behind the search form about `common\models\Academies`.
 */
class AcademiesSearch extends Academies
{
    public $sports = [];
    public $city;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'manager_id', 'parent_id', 'created_by','complete_profile', 'updated_by'], 'integer'],
            [['contact_phone', 'description', 'contact_email',
                'logo_path', 'logo_base_url', 'address', 'location',
                'lng', 'main', 'created_at', 'updated_at', 'district', 'sports'], 'safe'],
            ['title', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchOld($params)
    {
        $loggedInUserId = Yii::$app->user->id;
        $userProfile = UserProfile::findOne(['user_id' => $loggedInUserId]);
        $academy_id = $userProfile ? $userProfile->academy_id : null;

        $query = Academies::find();
        if ($academy_id) {
            $query->andWhere(['parent_id' => $academy_id]);
        } else {
            $query->where(['main' => 1]);
        }

        // Join with sports for filtering
        $query->joinWith('sports');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => 12,
//            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
//            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'manager_id' => $this->manager_id,
            'parent_id' => $this->parent_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'academies.title', $this->title])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'logo_path', $this->logo_path])
            ->andFilterWhere(['like', 'logo_base_url', $this->logo_base_url])
            ->andFilterWhere(['like', 'address', $this->address]) // Address filter
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'lng', $this->lng])
            ->andFilterWhere(['like', 'main', $this->main])
            ->andFilterWhere(['like', 'district', $this->district]);

        if (!empty($this->sports)) {
            $query->andFilterWhere(['sport.id' => $this->sports]);
        }

        return $dataProvider;
    }

    public function search($params)
    {
        $loggedInUserId = Yii::$app->user->id;
        $userProfile = UserProfile::findOne(['user_id' => $loggedInUserId]);
        $academy_id = $userProfile ? $userProfile->academy_id : null;

        $query = Academies::find()->distinct(true);

        if ($academy_id) {
            $query->andWhere(['parent_id' => $academy_id]);
        } else {
            $query->where(['main' => 1]);
        }

        // Join with sports for filtering
        $query->joinWith('sports');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 9,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'manager_id' => $this->manager_id,
            'parent_id' => $this->parent_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'academies.title', $this->title])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'logo_path', $this->logo_path])
            ->andFilterWhere(['like', 'logo_base_url', $this->logo_base_url])
            // ->andFilterWhere(['like', 'address', $this->address])
            // ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['or', ['like', 'address', $this->location],
                ['like', 'location', $this->location]])
            ->andFilterWhere(['like', 'lng', $this->lng])
            ->andFilterWhere(['like', 'main', $this->main])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'address', $this->city]);

        // Filtering by sports
        if (!empty($this->sports)) {
            $query->andFilterWhere(['sport.id' => $this->sports]);
        }

        return $dataProvider;
    }

    public function searchAdmin($params)
    {

        $query = Academies::find();

        if ($academy_id) {
            $query->andWhere(['parent_id' => $academy_id]);
        } else {
            $query->where(['main' => 1]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'manager_id' => $this->manager_id,
            'parent_id' => $this->parent_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'academies.title', $this->title])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'logo_path', $this->logo_path])
            ->andFilterWhere(['like', 'logo_base_url', $this->logo_base_url])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'lng', $this->lng])
            ->andFilterWhere(['like', 'main', $this->main])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'address', $this->city]);

        return $dataProvider;
    }


    public static function getDistinctValues($attribute, $academyId)
    {
        return ArrayHelper::map(Academies::find()
            ->select($attribute)
            ->distinct()
            ->where(['not', [$attribute => null]])
            ->andWhere(['parent_id' => $academyId]) // Filter by academy
            ->orderBy($attribute)
            ->asArray()
            ->all(), $attribute, $attribute);
    }
// In your AcademiesSearch or relevant model class

// public static function getDistinctAddresses($academyId = null)
// {
//     $query = Academies::find()
//         ->select(['city_postal' => new \yii\db\Expression('SUBSTRING_INDEX(SUBSTRING_INDEX(address, \',\', -2), \',\', 1)')])
//         ->distinct()
//         ->where(['not', ['address' => null]]);

//     if ($academyId) {
//         $query->andWhere(['parent_id' => $academyId]); 
//     }


//     return ArrayHelper::map($query->orderBy('city_postal')->asArray()->all(), 'city_postal', 'city_postal');
// }
    public static function getDistinctAddresses($academyId = null)
    {

        $query = Academies::find()
            ->select(['city_postal' => new \yii\db\Expression("
            CASE 
                WHEN address LIKE '%, Riyadh%' THEN 'Riyadh'
                WHEN address LIKE '%, Jeddah%' THEN 'Jeddah'
                WHEN address LIKE '%, Dammam%' THEN 'Dammam'
                ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(address, ',', -2), ',', 1) 
            END
        ")])
            ->distinct()
            ->where(['not', ['address' => null]]);

        if ($academyId) {
            $query->andWhere(['parent_id' => $academyId]);
        }

        return ArrayHelper::map($query->orderBy('city_postal')->asArray()->all(), 'city_postal', 'city_postal');
    }





}
