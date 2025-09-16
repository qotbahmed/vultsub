<?php

namespace common\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SubscriptionDetails;

/**
 * common\models\\SubscriptionManagementSreach represents the model behind the search form about `common\models\SubscriptionDetails`.
 */
class SubscriptionManagementSreach extends SubscriptionDetails
{
    public $mobile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'player_id', 'packages_id', 'sports_id', 'subscription_id', 'created_by', 'updated_by', 'classes', 'amount'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at', 'package_name', 'sport_name', 'mobile'], 'safe'],

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

    public function search($params)
    {

        $query = User::find()->joinWith(['userProfile', 'subscriptions']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user.id' => $this->id,
            'subscriptions.start_date' => $this->start_date,
            'subscriptions.end_date' => $this->end_date,
        ]);

        $query->andFilterWhere(['like', 'userProfile.firstname', $this->firstname])
            ->andFilterWhere(['like', 'userProfile.lastname', $this->lastname])
            ->andFilterWhere(['like', 'subscriptions.sport_name', $this->sport_name])
            ->andFilterWhere(['like', 'subscriptions.package_name', $this->package_name])
            ->andFilterWhere(['like', 'user.mobile', $this->mobile]);

        return $dataProvider;
    }
}
