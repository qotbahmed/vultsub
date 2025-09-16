<?php
namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Invoices;

class InvoicesSearch extends Invoices
{
    public $academyId;

    public static function tableName($academyId = null)
    {
        // The table name will be dynamically set based on the academy's id
        if ($academyId) {
            return 'invoices_' . $academyId;
        }
        return '{{%invoices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'type', 'related_id', 'academy_id'], 'integer'],
            [['amount', 'tax'], 'number'],
            [['qr_path', 'qr_base_url', 'created_at'], 'safe'],
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
        // Get the current academy
        $currentAcademy = Yii::$app->controller->academyMainObj;

        // Set the academy name depending to the main academy
        if (Yii::$app->controller->MainAcadmin) {
            $this->academyId = $currentAcademy->id;
        } else {
            $this->academyId = \common\models\Academies::find()
                ->select('id')
                ->where(['id' => $currentAcademy->parent_id])
                ->scalar();
        }

        // Use the dynamic table name based on the academyId
        $tableName = self::tableName($this->academyId);

        // Create a query using the dynamic table
        $query = Invoices::find()->from($tableName)->with('academy'); // Eager load the academy relationship

        // Filter by academy ID
        if (Yii::$app->controller->MainAcadmin) {
            // Main academy: fetch records for itself and all sub-academies
            $mainAcademy = \common\models\Academies::findOne($currentAcademy->id);
            $subAcademyIds = $mainAcademy->getAcademies()->select('id')->column();
            $academyIds = array_merge([$currentAcademy->id], $subAcademyIds);
            $query->andWhere(['academy_id' => $academyIds]);
        } else {
            // Sub-academy: fetch records for the current academy
            $query->andWhere(['academy_id' => $currentAcademy->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'serial_number' => $this->serial_number,
            'type' => $this->type,
            'related_id' => $this->related_id,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'academy_id' => $this->academy_id,
        ]);

        if ($this->created_at) {
            $date = \DateTime::createFromFormat('d-m-Y', $this->created_at);
            if ($date) {
                $formattedDate = $date->format('Y-m-d');
                $query->andFilterWhere(['DATE(created_at)' => $formattedDate]);
            }
        }

        $query->andFilterWhere(['like', 'qr_path', $this->qr_path])
            ->andFilterWhere(['like', 'qr_base_url', $this->qr_base_url]);

        return $dataProvider;
    }

}
