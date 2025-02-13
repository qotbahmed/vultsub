<?php

use yii\helpers\Url;
use yii\helpers\Html;

// Get current tab parameter
$tab = Yii::$app->request->get('tab', 'general');
?>

<div class="settings-container">

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?= ($tab == 'privacy') ? 'active' : '' ?>"
               href="<?= Url::to(['/settings/index', 'tab' => 'privacy']) ?>">
                <?= Yii::t('backend', 'الاعدادات العامة') ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($tab == 'account') ? 'active' : '' ?>"
               href="<?= Url::to(['/settings/index', 'tab' => 'account']) ?>">
                <?= Yii::t('backend', 'الخصوصية والحماية') ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($tab == 'point') ? 'active' : '' ?>"
               href="<?= Url::to(['/settings/index', 'tab' => 'point']) ?>">
                <?= Yii::t('backend', 'اعدادات النقاط') ?>
            </a>
        </li>
    </ul>

    <!-- Load the correct form based on the active tab -->
    <div class="tab-content">
        <?php
        if ($tab == 'point') {
            echo $this->render('_point', ['model' => $settingsModel]);
        } elseif ($tab == 'privacy') {
            echo $this->render('_privacy', ['model' => $profileModel]);
        } elseif ($tab == 'account') {
            echo $this->render('_account', ['model' => $accountModel]);
        }
        ?>
    </div>
</div>
