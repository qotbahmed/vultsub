<?php

namespace backend\models\search;

use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use backend\modules\rbac\models\RbacAuthItem;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{

    public $cities = [];
    public $districts= [];
    public $user_role;

    public $role_category;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status','role_category'], 'integer'],
            [['created_at', 'updated_at', 'logged_at'], 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            [['created_at', 'updated_at', 'logged_at'], 'default', 'value' => null],
            [['username', 'auth_key', 'password_hash', 'email', 'deleted_email', 'deleted_mobile','user_role'], 'safe'],

            [['hasPhone','hasPhoneVerified','cities','districts', 'duplicated', 'hasCity', 'hasDistrict', 'completedData'],'safe'],
            [['from_date','to_date','deactivation_date','hasSlug','sent_activation_message','featured', 'hasEmail'], 'safe']
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $query->joinWith(['userProfile'])
            ->join('LEFT JOIN','rbac_auth_assignment','rbac_auth_assignment.user_id = user.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            //return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);


        if($this->role_category){
            $query->innerJoin('rbac_auth_item','rbac_auth_assignment.item_name = rbac_auth_item.name')
                ->andFilterWhere(['rbac_auth_item.assignment_category' => RbacAuthItem::CUSTOM_ROLE_ASSIGN]);
        }

        if(!$this->role_category )
        {
            $query->innerJoin('rbac_auth_item','rbac_auth_assignment.item_name = rbac_auth_item.name')
                ->andWhere(['and',['is','assignment_category', new \yii\db\Expression('null')],
                    ['!=','name','administrator'],
                    ['!=','name','customRole']]);
        }


        if ($this->created_at !== null) {
            $query->andFilterWhere(['between', 'user.created_at', $this->created_at, $this->created_at + 3600 * 24]);
        }

        if ($this->updated_at !== null) {
            $query->andFilterWhere(['between', 'user.updated_at', $this->updated_at, $this->updated_at + 3600 * 24]);
        }

        if ($this->logged_at !== null) {
            $query->andFilterWhere(['between', 'logged_at', $this->logged_at, $this->logged_at + 3600 * 24]);
        }


        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'email', trim($this->email)]);


        if(! \Yii::$app->user->can('administrator')) {
            $query->andFilterWhere(['>', 'user.id', 1]);  //super admin

        }

        return $dataProvider;
    }


}
