<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $daysRemaining int */
/* @var $academyUrl string */

$this->title = 'Trial Expiring Soon';
?>

<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #ff6b35; margin: 0;">⏰ Trial Expiring Soon</h1>
        <p style="color: #666; margin: 10px 0 0 0;">Don't lose access to your academy management tools</p>
    </div>

    <div style="background: #fff3cd; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
        <h2 style="color: #856404; margin-top: 0;">Your trial expires in <?= $daysRemaining ?> day<?= $daysRemaining > 1 ? 's' : '' ?>!</h2>
        <p style="color: #856404; margin: 0;">Your free trial for <strong><?= Html::encode($user->academy_name) ?></strong> will end on 
        <strong><?= date('F j, Y', strtotime($user->trial_ends_at)) ?></strong>.</p>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 style="color: #333;">What happens when your trial ends?</h3>
        <ul style="color: #666;">
            <li>Your academy dashboard will be locked</li>
            <li>You won't be able to access student data</li>
            <li>All features will be disabled</li>
            <li>Your data will be safely stored for 30 days</li>
        </ul>
    </div>

    <div style="background: #d4edda; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h4 style="color: #155724; margin-top: 0;">✅ Don't worry - your data is safe!</h4>
        <p style="color: #155724; margin: 0;">We'll keep your academy data for 30 days after trial expiration, 
        so you can easily reactivate your account anytime.</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="<?= $academyUrl ?>/subscription" 
           style="background: #28a745; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px; font-weight: bold;">
            Choose Your Plan Now
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 style="color: #333;">Why upgrade now?</h3>
        <ul style="color: #666;">
            <li>Keep all your academy data and settings</li>
            <li>Continue managing your students and classes</li>
            <li>Access advanced features and reporting</li>
            <li>Get priority support</li>
            <li>Scale your academy without limits</li>
        </ul>
    </div>

    <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h4 style="color: #007bff; margin-top: 0;">💡 Special Offer</h4>
        <p style="color: #007bff; margin: 0;">Upgrade before your trial ends and get your first month at 50% off! 
        Use code <strong>TRIAL50</strong> at checkout.</p>
    </div>

    <div style="border-top: 1px solid #eee; padding-top: 20px; text-align: center; color: #666; font-size: 14px;">
        <p>Questions about our plans? Contact us at 
        <a href="mailto:support@vult-saas.com" style="color: #007bff;">support@vult-saas.com</a></p>
        <p>© <?= date('Y') ?> Vult SaaS. All rights reserved.</p>
    </div>
</div>
