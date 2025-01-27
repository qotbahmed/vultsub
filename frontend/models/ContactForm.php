<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $contact_option;
    public $company;
    public $country;
    public $field;
    public $position;
    public $years_of_experience;
    public $reCaptcha;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name'], 'required','message'=>Yii::t('frontend','Please Enter your name')],
            //[['phone'], 'required','message'=>Yii::t('frontend','Please Enter your phone')],
            //[['email'], 'required','message'=>Yii::t('frontend','Please Enter your email')],
            [['subject'], 'required','message'=>Yii::t('frontend','Please Enter your company')],
            [['body'], 'required','message'=>Yii::t('frontend','Please Enter your message')],
           // [['verifyCode'], 'required','message'=>Yii::t('frontend','Please Enter valid code')],
            [['contact_option'], 'required','message'=>Yii::t('frontend','Please choose your preferred option')],
            // We need to sanitize them
            [['name','phone',  'subject', 'body'], 'filter', 'filter' => 'strip_tags'],
            // email has to be a valid email address
            ['email', 'email','message'=>Yii::t('frontend','Please Enter valid email')],
            // verifyCode needs to be entered correctly
           // ['verifyCode', 'captcha'],
            [['contact_option','phone'],'integer','message'=>Yii::t('frontend','Please Enter a valid phone')],

            [['company','country','field','position'],'string','max'=>150],
            ['years_of_experience','number','min'=>1,'max'=>30],

            [['company'], 'required','on'=>"Partner",'message'=>Yii::t('frontend','Please Enter your Company')],
            [['field'], 'required','on'=>"Partner",'message'=>Yii::t('frontend','Please Enter your Field')],
            [['position'], 'required','on'=>"Partner",'message'=>Yii::t('frontend','Please Enter your Position')],
            [['years_of_experience'], 'required','on'=>"Partner",'message'=>Yii::t('frontend','Please Enter your Experience')],

            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(),
                //'secret' => 'your secret key', // unnecessary if reСaptcha is already configured
                'uncheckedMessage' => Yii::t('frontend','Please prove that you are a real person') ],



        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('frontend', 'Full Name'),
            'phone' => Yii::t('frontend', 'Phone'),
            'email' => Yii::t('frontend', 'Email'),
            'subject' => Yii::t('frontend', 'Subject'),
            'body' => Yii::t('frontend', 'Body'),
            'verifyCode' => Yii::t('frontend', 'Verification Code'),
            'contact_option' => Yii::t('frontend', 'What option you are interested in?')
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        return true;
        if ($this->validate()) {
            return Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom(Yii::$app->params['robotEmail'])
                ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();
        } else {
            return false;
        }
    }
}
