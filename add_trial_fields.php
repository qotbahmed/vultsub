<?php
$userModel = file_get_contents('common/models/User.php');

// Add trial fields to properties
$trialFields = ' * @property integer $trial_started_at
 * @property integer $trial_expires_at
 * @property integer $academy_id';

// Find the position to insert after the last @property
$pos = strrpos($userModel, '@property \common\models\Academies $academy');
if ($pos !== false) {
    $pos = strpos($userModel, "\n", $pos);
    $userModel = substr_replace($userModel, "\n" . $trialFields, $pos, 0);
}

// Add trial fields to rules
$trialRules = "            [['trial_started_at', 'trial_expires_at', 'academy_id'], 'integer'],";

// Find the position to insert after the last rule
$pos = strrpos($userModel, "['available_for_booking'], 'boolean'],");
if ($pos !== false) {
    $pos = strpos($userModel, "\n", $pos);
    $userModel = substr_replace($userModel, "\n            " . $trialRules, $pos, 0);
}

file_put_contents('common/models/User.php', $userModel);
echo "Trial fields added to User model\n";
