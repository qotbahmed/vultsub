<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $academyUrl string */

$this->title = 'Welcome to Vult SaaS';
?>

<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #007bff; margin: 0;">Welcome to Vult SaaS!</h1>
        <p style="color: #666; margin: 10px 0 0 0;">Your Academy Management Platform</p>
    </div>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="color: #333; margin-top: 0;">Hello <?= Html::encode($user->getFullName()) ?>!</h2>
        <p>Welcome to Vult SaaS! We're excited to help you manage your academy, <strong><?= Html::encode($user->academy_name) ?></strong>.</p>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 style="color: #333;">Your Academy Details:</h3>
        <ul style="color: #666;">
            <li><strong>Academy Name:</strong> <?= Html::encode($user->academy_name) ?></li>
            <li><strong>Subdomain:</strong> <?= Html::encode($user->subdomain) ?>.vult-saas.com</li>
            <li><strong>Branches:</strong> <?= $user->branches_count ?></li>
            <li><strong>Trial Period:</strong> 7 days free</li>
        </ul>
    </div>

    <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="color: #007bff; margin-top: 0;">🎉 You're on a 7-day free trial!</h3>
        <p style="margin: 0;">Your trial expires on <strong><?= date('F j, Y', strtotime($user->trial_ends_at)) ?></strong>. 
        During this time, you have full access to all features.</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="<?= $academyUrl ?>" 
           style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Access Your Academy Dashboard
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 style="color: #333;">What's Next?</h3>
        <ol style="color: #666;">
            <li>Complete your academy setup</li>
            <li>Add your students and staff</li>
            <li>Create your first class schedule</li>
            <li>Explore all the features during your trial</li>
        </ol>
    </div>

    <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h4 style="color: #856404; margin-top: 0;">💡 Pro Tip</h4>
        <p style="color: #856404; margin: 0;">Make the most of your trial by setting up your academy completely. 
        This will help you see the full value of our platform before your trial ends.</p>
    </div>

    <div style="border-top: 1px solid #eee; padding-top: 20px; text-align: center; color: #666; font-size: 14px;">
        <p>Need help getting started? Contact our support team at 
        <a href="mailto:support@vult-saas.com" style="color: #007bff;">support@vult-saas.com</a></p>
        <p>© <?= date('Y') ?> Vult SaaS. All rights reserved.</p>
    </div>
</div>
