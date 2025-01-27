<?php

namespace frontend\models\search;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class ProfileSearch extends User
{
    public $user_role;
    public $fullName;
    public $academy_id; // إضافة الخاصية هنا

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'academy_id'], 'integer'], // إضافة academy_id هنا
            [['mobile', 'fullName'], 'string'],
            [['filterType', 'mobile'], 'safe'],
            [['created_at', 'updated_at', 'logged_at'], 'default', 'value' => null],
            [['username', 'auth_key', 'password_hash', 'email', 'user_role', 'status'], 'safe'],
            [['firstname', 'lastname', 'dob', 'sport_names', 'package_names', 'start_date', 'end_date'], 'safe'],
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
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()->where(['user_type' => User::USER_TYPE_PLAYER])
            ->andWhere(['!=', 'status', User::STATUS_DELETED])
            ->andWhere(['parent_id'=>Yii::$app->user->identity->id])
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->joinWith(['userProfile' => function ($q) {
            $q->andFilterWhere(['like', 'user_profile.firstname', $this->fullName]);
        }]);

        if ($this->user_role) {
            $query->join('LEFT JOIN', '{{%rbac_auth_assignment}}', '{{%rbac_auth_assignment}}.user_id = {{%user}}.id')
                ->andFilterWhere(['{{%rbac_auth_assignment}}.item_name' => $this->user_role]);
        }

        $query->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['>', 'id', 3]); //super admin

        if ($this->created_at !== null) {
            $query->andFilterWhere(['between', 'user.created_at', strtotime($this->created_at), strtotime($this->created_at) + (3600 * 24)]);
        }

        if ($this->updated_at !== null) {
            $query->andFilterWhere(['between', 'user.updated_at', strtotime($this->updated_at), strtotime($this->updated_at) + 3600 * 24]);
        }

        if ($this->logged_at !== null) {
            $query->andFilterWhere(['between', 'user.logged_at', strtotime($this->logged_at), strtotime($this->logged_at) + 3600 * 24]);
        }

        // فلترة باستخدام academy_id
        // if ($this->academy_id) {
        //     $query->joinWith(['userProfile' => function ($q) {
        //         $q->andFilterWhere(['user_profile.academy_id' => $this->academy_id]);
        //     }]);
        // }
        
        // Filter by academy_id, including branches of the selected main academy
        if ($this->academy_id !== null && $this->academy_id !== '') {
            // Get the main academy and all related branches (sub-academies)
            $academyIds = \common\models\Academies::find()
                ->select('id')
                ->where(['or', ['id' => $this->academy_id], ['parent_id' => $this->academy_id]]) // Main and branches
                ->column();
        
            // Filter users by the academy_ids (main and branch academies)
            $query->joinWith(['userProfile' => function ($q) use ($academyIds) {
                $q->andFilterWhere(['user_profile.academy_id' => $academyIds]);
            }]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'user.mobile', $this->mobile])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }


    
}