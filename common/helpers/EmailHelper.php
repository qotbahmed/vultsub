<?php
namespace common\helpers;


class EmailHelper
{
    public $fromName;
    public $fromEmail;
    public $toEmail;
    public $replyTo;
    public $title;
    public $bccEmail;
    public $tag;
    public $message;
    public $params;

    public static function instance()
    {
        return new self;
    }

    public function __construct()
    {
        $this->tag = 'Emails';
        $this->fromName = 'app';
        $this->fromEmail = env('FROM_EMAIL');  //''support@app.com';
        $this->replyTo = 'info@app.com';
        $this->title = 'app Notification';
        $this->message = 'Welcome To app';
        $this->params = ['message' => $this->message];
    }

    public function Send($mail_template = 'general')
    {
       // $to = $this->toEmail;
        $to = 'test@gmail.com';
        //check environment
        if (YII_ENV_DEV ) {
         //   $to = env('SEND_TO_EMAIL', 'test@gmail.com');
        }

        try {
            \Yii::$app->mailer->compose($mail_template, $this->params)
                ->setFrom($this->fromEmail)
                ->setTo($to)
                ->setReplyTo($this->replyTo)
                ->setSubject($this->title)
                ->send();
            return true;

        }catch (\Exception $e) {
            return false;
            // echo $e->getCode();
        }


    }

    /************************************* Emails ************************************/

    public function SendWelcome($to, $message = "")
    {
        $this->tag = 'Amer';
        $this->toEmail = $to;
        $this->message = $message ?: 'Hi there ';
        $this->params = ['message' => $message];
        $this->Send('general');
    }

    public function SendNewInvitation($model,$to= 'test@gmail.com')
    {
        $title = 'Your friend Invites you to join';
        $this->params = ['model' => $model];
        $this->toEmail = $model->invitee_email ;
        $this->title = $title;
        $this->Send('new_invitations');
    }

}
