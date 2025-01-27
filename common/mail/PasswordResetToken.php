<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $token string */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/sign-in/reset-password', 'token' => $token]);
$domain = env('FRONTEND_HOST_INFO');

?>

<tr>
                    <td
                        class="container-padding content"
                        align="left"
                        style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff"
                        >
                        <br />
                        <div
                            class="title"
                            style="font-family: 'Cairo', sans-serif;font-size:18px;font-weight:600;color:#374550;text-align: left"
                            >
                            أهلاً  <?php echo Html::encode($user->username) ?>
                        </div>
                        <br />
                        <div
                            class="body-text"
                            style="font-family: 'Cairo', sans-serif;font-size:14px;line-height:20px;text-align:right;color:#333333"
                            >
                            يبدوا أنك لم تستطع الدخول الي حسابك لدينا, هل أضعت كلمة المرور
                            الخاصة بك؟!
                            <br /><br />
                            يرجي استخدام اللينك التالي لتغيير كلمة المرور الخاصة بحسابك,
                            اذا لم يكن انت من حاولت تسجيل الدخول يرجي عدم الالتفات لهذه
                            الرسالة.
                            <br /><br />

                            <a
                                href="<?= $resetLink; ?>"
                                style="line-height:44px;padding: 0 55px;font-size: 14px;text-transform: uppercase;font-weight: 700;background: #F05040;color: #fff;display: inline-block;margin-top: 25px;box-shadow: none;-webkit-box-shadow: none;text-decoration: none;"
                                >تغيير كلمة المرور</a
                                >
                            <br /><br />
                        </div>
                    </td>
</tr>

