<?php

use yii\helpers\Html;

?>

<tr>
    <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
        <br />
        <div class="title" style="font-family: 'Cairo', sans-serif;font-size:18px;font-weight:600;color:#374550;text-align: left">
            أهلاً <?php echo Html::encode($user->username) ?>
        </div>
        <br />
        <div class="body-text" style="font-family: 'Cairo', sans-serif;font-size:14px;line-height:20px;text-align:right;color:#333333">
            <h2><?= $token ?></h2>

            يرجي استخدام الرمز السابق لتأكيد البريد اﻷلكتروني الخاص بحسابك,
            اذا لم يكن انت من حاولت تسجيل الدخول يرجي عدم الالتفات لهذه
            الرسالة.
            <br /><br />
        </div>
    </td>
</tr>