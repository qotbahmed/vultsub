<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VitalSign;
use common\models\UserProfile;



/**
 * common\models\search\VitalSignSearch represents the model behind the search form about `common\models\VitalSign`.
 */
 class VitalSignSearch extends VitalSign
{
    public $fullName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'medical_history_id', 'age', 'player_id'], 'integer'],
            [['updated_at', 'created_at', 'date_of_birth', 'gender', 'body_condition','fullName'], 'safe'],
            [['Weight', 'height', 'bmi'], 'number'],
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

 
    
// public function search($params)
// {
//     // Start the query with VitalSign
//     $query = VitalSign::find();
//     // Join with userProfile and user to access player-related information
//     $query->joinWith(['userProfile.user']);
//     // Create the ActiveDataProvider
//     $dataProvider = new ActiveDataProvider([
//         'query' => $query,
//     ]);
//     // Load the search parameters
//     $this->load($params);
//     // Validate the parameters
//     if (!$this->validate()) {
//         return $dataProvider; // Return without filtering if validation fails
//     }
//     // Apply filtering for the VitalSign fields
//     $query->andFilterWhere([
//         'id' => $this->id,
//         'updated_at' => $this->updated_at,
//         'created_at' => $this->created_at,
//         'created_by' => $this->created_by,
//         'updated_by' => $this->updated_by,
//         'medical_history_id' => $this->medical_history_id,
//         'Weight' => $this->Weight,
//         'height' => $this->height,
//         'bmi' => $this->bmi,
//         'age' => $this->age,
//         'date_of_birth' => $this->date_of_birth,
//         'player_id' => $this->player_id,
//     ]);
//     // Filter based on related user profile fields
//     $query->andFilterWhere(['like', 'user_profile.firstname', $this->fullName])
//           ->andFilterWhere(['like', 'user_profile.gender', $this->gender])
//           ->andFilterWhere(['like', 'body_condition', $this->body_condition]);
//     // Apply the academy filter using the main academy's ID
//     if (isset(Yii::$app->controller->academyMainObj)) {
//         $query->andWhere(['user_profile.academy_id' => Yii::$app->controller->academyMainObj->id]);
//     }
//     // Additional filter to only include users with user_type = 1 (players)
//     $query->andWhere(['user.user_type' => 1]);
//     return $dataProvider;
// }
public function search($params, $player_id = null)
{
    $query = VitalSign::find();

    if ($player_id !== null) {
        $query->andWhere(['player_id' => $player_id]);
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => 20],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere([
        'id' => $this->id,
        'Weight' => $this->Weight,
        'height' => $this->height,
        'bmi' => $this->bmi,
        'age' => $this->age,
    ]);

    return $dataProvider;
}
  
    

    
}
