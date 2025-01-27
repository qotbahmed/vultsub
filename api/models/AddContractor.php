<?php
namespace api\models;

use common\models\base\Country;
use common\models\UserProfile;
use Yii;
use yii\base\Model;
use common\models\User;
use common\models\CompanyUserContract;
use yii\web\UploadedFile;

class AddContractor extends Model
{
    // Contract Info
    public $job_title;
    public $email;
    public $tax_country_id;
    public $job_desc;

    // Payment
    public $contract_start_date;
    public $contract_end_date;
    public $amount;
    public $amount_currency;
    public $payment_frequancy;
    public $first_payment_date;
    public $first_payment_amount;
    public $first_payment_prorate;

    // Compliance
    public $notice_period;
    public $contract_file;

    public function rules()
    {
        return [
            // Contract Info
            [['job_title', 'email', 'tax_country_id', 'job_desc'], 'required'],
            ['email', 'email'],
            [['job_title', 'job_desc'], 'string'],

            // Payment
            [['contract_start_date', 'contract_end_date', 'first_payment_date'], 'date', 'format' => 'php:Y-m-d'],
            [['amount'], 'number'],
            [['amount_currency', 'payment_frequancy'], 'string'],
            [['first_payment_prorate'], 'boolean'],
            [['first_payment_date'], 'date', 'format' => 'php:Y-m-d'],
            [['tax_country_id'], 'integer'],
            ['tax_country_id', 'exist', 'targetClass' => Country::class, 'targetAttribute' => 'id', 'message' => 'The selected tax country is invalid.'],

            // Compliance
            [['notice_period'], 'string'],
            [['contract_file','first_payment_amount'], 'safe'], // Handle file uploads
        ];
    }

    public function addContractor()
    {
        if (!$this->validate()) {
            return [
                'status' => false,
                'errors' => $this->getFirstErrors(),
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $company_id = Yii::$app->user->identity->id; // Logged-in company ID

            // Check if contractor exists or create a new user
            $contractor = User::findOne(['email' => $this->email]);
            $user = User::findOne( Yii::$app->user->identity->id);
            $user->add_first_contact=1;
            $user->save();
            if ($contractor){
                return [
                    'status' => false,
                    'errors' => "email already exists",
                ];
            }

            $contractor = new User();
            $contractor->email = $this->email;
            $contractor->username = $this->email;
            $contractor->account_type = 'contractor';
            $contractor->setPassword(Yii::$app->security->generateRandomString(8)); // Temporary password
            $contractor->status = User::STATUS_NOT_ACTIVE;
            $contractor->generateAuthKey();
            if ($contractor->save()) {
                $auth = Yii::$app->authManager;
                $role = $auth->getRole(User::ROLE_USER);
                $auth->assign($role, $contractor->id);

                $profile = new UserProfile();
                $profile->firstname = 'contractor';
                $profile->lastname = '#10000' . $contractor->id;
                $profile->locale = 'en-US';

                $contractor->link('userProfile', $profile);
            }else{
                throw new \Exception('Failed to create contractor: ' . implode(', ', $contractor->getFirstErrors()));
            }


            // Save the contract details
            $contract = new CompanyUserContract();
            $contract->contractor_id = $contractor->id;
            $contract->company_id = $company_id;
            $contract->contract_type = $this->job_title;
            $contract->job_title = $this->job_title;
            $contract->job_desc = $this->job_desc;
            $contract->notice_period = $this->notice_period;
            $contract->contract_start_date = $this->contract_start_date;
            $contract->contract_end_date = $this->contract_end_date;
            $contract->amount = $this->amount;
            $contract->amount_currency = $this->amount_currency;
            $contract->payment_frequancy = $this->payment_frequancy;
            $contract->tax_country_id = $this->tax_country_id;
            $contract->first_payment_date = $this->first_payment_date;
            $contract->first_payment_amount = $this->first_payment_amount;
            $contract->status = CompanyUserContract::STATUS_INACTIVE;

            if (!$contract->save()) {
                throw new \Exception('Failed to save contract: ' . implode(', ', $contract->getFirstErrors()));
            }

            // Save the contract file if provided
    //            if ($this->contract_file) {
    //                $filePath = Yii::getAlias('@webroot/uploads/contracts/') . uniqid('contract_') . '.pdf';
    //                $decodedFile = base64_decode($this->contract_file);
    //                if (!file_put_contents($filePath, $decodedFile)) {
    //                    throw new \Exception('Failed to save contract file.');
    //                }
    //            }

            $transaction->commit();
            return [
                'status' => true,
                'message' => 'Contractor added successfully.',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'status' => false,
                'errors' => [$e->getMessage()],
            ];
        }
    }
}
