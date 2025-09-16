# Trial System Guide - Vult Subscription System

## Overview
This guide provides comprehensive information about the trial system implementation in the Vult Subscription System, including how it works, configuration options, and management procedures.

## Trial System Architecture

### Core Components
1. **Trial Fields**: Added to user table for tracking trial periods
2. **Trial Methods**: User model methods for trial management
3. **Trial Validation**: Business logic for trial status checking
4. **Trial Notifications**: Email alerts for trial status changes
5. **Trial Cron Jobs**: Automated trial management tasks

## Database Schema

### User Table Trial Fields
```sql
-- Trial management fields in user table
trial_started_at INT NULL COMMENT 'Trial start timestamp'
trial_expires_at INT NULL COMMENT 'Trial expiry timestamp'
academy_id INT NULL COMMENT 'Associated academy ID'

-- Indexes for performance
CREATE INDEX idx_user_trial_started_at ON user (trial_started_at);
CREATE INDEX idx_user_trial_expires_at ON user (trial_expires_at);
CREATE INDEX idx_user_academy_id ON user (academy_id);
```

## Trial Management Methods

### User Model Methods

#### Check Trial Status
```php
/**
 * Check if user is on trial
 * @return bool
 */
public function isTrial()
{
    return $this->trial_expires_at && $this->trial_expires_at > time();
}

/**
 * Check if trial has expired
 * @return bool
 */
public function isTrialExpired()
{
    return $this->trial_expires_at && $this->trial_expires_at <= time();
}

/**
 * Get remaining trial days
 * @return int
 */
public function getTrialDaysLeft()
{
    if (!$this->trial_expires_at) {
        return 0;
    }
    
    $daysLeft = ceil(($this->trial_expires_at - time()) / (24 * 60 * 60));
    return max(0, $daysLeft);
}
```

#### Trial Control Methods
```php
/**
 * Start trial period for user
 * @param int $days Number of trial days (default 7)
 * @return bool
 */
public function startTrial($days = 7)
{
    $this->trial_started_at = time();
    $this->trial_expires_at = time() + ($days * 24 * 60 * 60);
    return $this->save();
}

/**
 * End trial period for user
 * @return bool
 */
public function endTrial()
{
    $this->trial_expires_at = time();
    return $this->save();
}
```

## Trial Workflow

### 1. Trial Initiation
```php
// When a new academy request is approved
$user = User::findByEmail($academyRequest->email);
if ($user) {
    // Start 7-day trial
    $user->startTrial(7);
    
    // Send welcome email
    Yii::$app->mailer->compose('trial-welcome', ['user' => $user])
        ->setTo($user->email)
        ->setSubject('Welcome to Vult - Your Trial Has Started')
        ->send();
}
```

### 2. Trial Status Checking
```php
// Check if user is on trial
if ($user->isTrial()) {
    $daysLeft = $user->getTrialDaysLeft();
    echo "You have {$daysLeft} days left in your trial";
}

// Check if trial has expired
if ($user->isTrialExpired()) {
    // Redirect to upgrade page
    return $this->redirect(['/upgrade']);
}
```

### 3. Trial Expiry Handling
```php
// Automated trial expiry check
$expiredUsers = User::find()
    ->where(['<', 'trial_expires_at', time()])
    ->andWhere(['>', 'trial_expires_at', 0])
    ->all();

foreach ($expiredUsers as $user) {
    // Disable user account
    $user->status = User::STATUS_NOT_ACTIVE;
    $user->save();
    
    // Send expiry notification
    Yii::$app->mailer->compose('trial-expired', ['user' => $user])
        ->setTo($user->email)
        ->setSubject('Your Trial Has Expired')
        ->send();
}
```

## Configuration Options

### Trial Duration
```php
// Default trial duration (7 days)
const DEFAULT_TRIAL_DAYS = 7;

// Configurable trial durations
const TRIAL_DURATIONS = [
    'basic' => 7,      // 7 days for basic plan
    'premium' => 14,   // 14 days for premium plan
    'enterprise' => 30 // 30 days for enterprise plan
];
```

### Trial Limits
```php
// Maximum number of players during trial
const TRIAL_MAX_PLAYERS = 50;

// Maximum number of branches during trial
const TRIAL_MAX_BRANCHES = 2;

// Trial feature restrictions
const TRIAL_RESTRICTIONS = [
    'max_players' => 50,
    'max_branches' => 2,
    'advanced_reports' => false,
    'api_access' => false,
    'custom_branding' => false
];
```

## Trial Notifications

### Email Templates

#### Welcome Email
```php
// common/mail/trial-welcome.php
<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\User $user */
?>
<h1>Welcome to Vult!</h1>
<p>Hello <?= Html::encode($user->username) ?>,</p>
<p>Your 7-day free trial has started. You now have access to all Vult features.</p>
<p><strong>Trial Details:</strong></p>
<ul>
    <li>Start Date: <?= date('Y-m-d H:i:s', $user->trial_started_at) ?></li>
    <li>End Date: <?= date('Y-m-d H:i:s', $user->trial_expires_at) ?></li>
    <li>Days Remaining: <?= $user->getTrialDaysLeft() ?></li>
</ul>
<p>
    <?= Html::a('Access Your Dashboard', Url::to(['/dashboard'], true)) ?>
</p>
```

#### Trial Expiry Warning
```php
// common/mail/trial-expiry-warning.php
<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\User $user */
?>
<h1>Trial Expiry Warning</h1>
<p>Hello <?= Html::encode($user->username) ?>,</p>
<p>Your trial will expire in <?= $user->getTrialDaysLeft() ?> days.</p>
<p>To continue using Vult, please upgrade to a paid plan.</p>
<p>
    <?= Html::a('Upgrade Now', Url::to(['/upgrade'], true)) ?>
</p>
```

#### Trial Expired
```php
// common/mail/trial-expired.php
<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\User $user */
?>
<h1>Trial Expired</h1>
<p>Hello <?= Html::encode($user->username) ?>,</p>
<p>Your trial period has ended. Your account has been temporarily suspended.</p>
<p>To reactivate your account, please upgrade to a paid plan.</p>
<p>
    <?= Html::a('Upgrade Now', Url::to(['/upgrade'], true)) ?>
</p>
```

## Cron Jobs

### Trial Management Cron
```php
<?php
// console/controllers/TrialController.php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

class TrialController extends Controller
{
    /**
     * Check and handle expired trials
     */
    public function actionCheckExpired()
    {
        $this->stdout("Checking expired trials...\n");
        
        $expiredUsers = User::find()
            ->where(['<', 'trial_expires_at', time()])
            ->andWhere(['>', 'trial_expires_at', 0])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->all();
        
        $count = 0;
        foreach ($expiredUsers as $user) {
            $this->handleExpiredTrial($user);
            $count++;
        }
        
        $this->stdout("Processed {$count} expired trials\n");
        return ExitCode::OK;
    }
    
    /**
     * Send trial expiry warnings
     */
    public function actionSendWarnings()
    {
        $this->stdout("Sending trial expiry warnings...\n");
        
        // Users with 2 days left
        $warningUsers = User::find()
            ->where(['between', 'trial_expires_at', time() + (2 * 24 * 60 * 60), time() + (3 * 24 * 60 * 60)])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->all();
        
        $count = 0;
        foreach ($warningUsers as $user) {
            $this->sendTrialWarning($user);
            $count++;
        }
        
        $this->stdout("Sent {$count} trial warnings\n");
        return ExitCode::OK;
    }
    
    private function handleExpiredTrial($user)
    {
        // Disable user account
        $user->status = User::STATUS_NOT_ACTIVE;
        $user->save();
        
        // Send expiry notification
        Yii::$app->mailer->compose('trial-expired', ['user' => $user])
            ->setTo($user->email)
            ->setSubject('Your Vult Trial Has Expired')
            ->send();
    }
    
    private function sendTrialWarning($user)
    {
        Yii::$app->mailer->compose('trial-expiry-warning', ['user' => $user])
            ->setTo($user->email)
            ->setSubject('Your Vult Trial Expires Soon')
            ->send();
    }
}
```

### Cron Schedule
```bash
# Add to crontab
# Check expired trials daily at 9 AM
0 9 * * * /path/to/php /path/to/yii trial/check-expired

# Send warnings daily at 10 AM
0 10 * * * /path/to/php /path/to/yii trial/send-warnings
```

## API Endpoints

### Trial Status API
```php
// api/controllers/TrialController.php

namespace api\controllers;

use common\models\User;
use yii\rest\Controller;
use yii\web\Response;

class TrialController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    
    /**
     * Get trial status for user
     * GET /api/trial/status/{user_id}
     */
    public function actionStatus($user_id)
    {
        $user = User::findOne($user_id);
        
        if (!$user) {
            return [
                'success' => false,
                'error' => 'User not found'
            ];
        }
        
        return [
            'success' => true,
            'data' => [
                'is_trial' => $user->isTrial(),
                'is_expired' => $user->isTrialExpired(),
                'days_left' => $user->getTrialDaysLeft(),
                'trial_started_at' => $user->trial_started_at,
                'trial_expires_at' => $user->trial_expires_at
            ]
        ];
    }
    
    /**
     * Start trial for user
     * POST /api/trial/start
     */
    public function actionStart()
    {
        $user_id = Yii::$app->request->post('user_id');
        $days = Yii::$app->request->post('days', 7);
        
        $user = User::findOne($user_id);
        
        if (!$user) {
            return [
                'success' => false,
                'error' => 'User not found'
            ];
        }
        
        if ($user->startTrial($days)) {
            return [
                'success' => true,
                'message' => 'Trial started successfully',
                'data' => [
                    'trial_started_at' => $user->trial_started_at,
                    'trial_expires_at' => $user->trial_expires_at,
                    'days_left' => $user->getTrialDaysLeft()
                ]
            ];
        }
        
        return [
            'success' => false,
            'error' => 'Failed to start trial'
        ];
    }
}
```

## Frontend Integration

### Trial Status Widget
```php
// frontend/widgets/TrialStatusWidget.php

namespace frontend\widgets;

use common\models\User;
use yii\base\Widget;

class TrialStatusWidget extends Widget
{
    public $user;
    
    public function run()
    {
        if (!$this->user || !$this->user->isTrial()) {
            return '';
        }
        
        return $this->render('trial-status', [
            'user' => $this->user,
            'daysLeft' => $this->user->getTrialDaysLeft()
        ]);
    }
}
```

### Trial Status View
```php
<!-- frontend/widgets/views/trial-status.php -->
<div class="trial-status alert alert-warning">
    <h4>Free Trial</h4>
    <p>You have <strong><?= $daysLeft ?></strong> days left in your trial.</p>
    <a href="/upgrade" class="btn btn-primary">Upgrade Now</a>
</div>
```

## Testing

### Unit Tests
```php
// tests/unit/models/UserTrialTest.php

namespace tests\unit\models;

use common\models\User;
use Codeception\Test\Unit;

class UserTrialTest extends Unit
{
    public function testStartTrial()
    {
        $user = new User();
        $user->username = 'testuser';
        $user->email = 'test@example.com';
        $user->setPassword('password');
        $user->save();
        
        $this->assertTrue($user->startTrial(7));
        $this->assertTrue($user->isTrial());
        $this->assertEquals(7, $user->getTrialDaysLeft());
    }
    
    public function testTrialExpiry()
    {
        $user = new User();
        $user->trial_expires_at = time() - 3600; // 1 hour ago
        
        $this->assertTrue($user->isTrialExpired());
        $this->assertFalse($user->isTrial());
        $this->assertEquals(0, $user->getTrialDaysLeft());
    }
}
```

## Monitoring and Analytics

### Trial Metrics
```php
// console/controllers/StatsController.php

public function actionTrialStats()
{
    $stats = [
        'active_trials' => User::find()
            ->where(['>', 'trial_expires_at', time()])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->count(),
        
        'expired_trials' => User::find()
            ->where(['<', 'trial_expires_at', time()])
            ->andWhere(['>', 'trial_expires_at', 0])
            ->count(),
        
        'conversion_rate' => $this->calculateConversionRate(),
        
        'average_trial_duration' => $this->calculateAverageTrialDuration()
    ];
    
    return $stats;
}
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintainer:** Vult Development Team
