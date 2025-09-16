<?php
// cron/trial-management.php
// Script to manage trial accounts (e.g., check expiry, send notifications, disable accounts)

$servername = "database";
$username = "root";
$password = "root";
$dbname = "vult";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Running trial management cron job...\n";

$current_time = time();
$one_day_ago = $current_time - (1 * 24 * 60 * 60);
$seven_days_ago = $current_time - (7 * 24 * 60 * 60);

// 1. Identify trials expiring soon (in 1 day)
$sql_expiring_soon = "SELECT id, email, trial_expires_at, academy_id FROM user 
                      WHERE trial_expires_at IS NOT NULL 
                      AND trial_expires_at > ? 
                      AND trial_expires_at <= ? 
                      AND academy_id IS NULL";
$stmt = $conn->prepare($sql_expiring_soon);
$one_day_from_now = $current_time + (1 * 24 * 60 * 60);
$stmt->bind_param("ii", $current_time, $one_day_from_now);
$stmt->execute();
$result = $stmt->get_result();

$expiring_soon_count = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Trial expiring soon for user: " . $row['email'] . " (ID: " . $row['id'] . ")\n";
        
        // Send notification email (in real app)
        sendExpiryNotification($row['email'], $row['trial_expires_at']);
        
        $expiring_soon_count++;
    }
}
$stmt->close();

// 2. Identify expired trials and disable accounts
$sql_expired_trials = "SELECT id, email, trial_expires_at, academy_id FROM user 
                       WHERE trial_expires_at IS NOT NULL 
                       AND trial_expires_at <= ? 
                       AND academy_id IS NULL";
$stmt = $conn->prepare($sql_expired_trials);
$stmt->bind_param("i", $current_time);
$stmt->execute();
$result = $stmt->get_result();

$expired_count = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Trial expired for user: " . $row['email'] . " (ID: " . $row['id'] . "). Disabling account.\n";
        
        // Disable the account by setting status to inactive
        $update_sql = "UPDATE user SET status = 0 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
        
        // Send expiry notification
        sendTrialExpiredNotification($row['email']);
        
        $expired_count++;
    }
}
$stmt->close();

// 3. Clean up very old expired trial data (optional)
$thirty_days_ago = $current_time - (30 * 24 * 60 * 60);
$sql_cleanup_old_trials = "DELETE FROM user 
                           WHERE trial_expires_at IS NOT NULL 
                           AND trial_expires_at <= ? 
                           AND academy_id IS NULL 
                           AND status = 0";
$stmt = $conn->prepare($sql_cleanup_old_trials);
$stmt->bind_param("i", $thirty_days_ago);
$stmt->execute();
$cleaned_count = $stmt->affected_rows;
$stmt->close();

// 4. Update academy_requests status for expired trials
$sql_update_requests = "UPDATE academy_requests ar 
                        JOIN user u ON ar.email = u.email 
                        SET ar.status = 'expired' 
                        WHERE u.trial_expires_at IS NOT NULL 
                        AND u.trial_expires_at <= ? 
                        AND u.academy_id IS NULL 
                        AND ar.status = 'pending'";
$stmt = $conn->prepare($sql_update_requests);
$stmt->bind_param("i", $current_time);
$stmt->execute();
$updated_requests = $stmt->affected_rows;
$stmt->close();

echo "Trial management completed:\n";
echo "- Trials expiring soon: $expiring_soon_count\n";
echo "- Trials expired and disabled: $expired_count\n";
echo "- Old expired accounts cleaned: $cleaned_count\n";
echo "- Academy requests updated: $updated_requests\n";

$conn->close();

function sendExpiryNotification($email, $expires_at) {
    $days_left = ceil(($expires_at - time()) / (24 * 60 * 60));
    $subject = "تنبيه: تجربتك المجانية تنتهي قريباً";
    $message = "
    <h2>تنبيه انتهاء التجربة</h2>
    <p>مرحباً،</p>
    <p>تجربتك المجانية في Vult ستنتهي خلال $days_left أيام.</p>
    <p>يرجى الترقية للاستمرار في استخدام المنصة.</p>
    <p>رابط الترقية: <a href='http://vult-saas.localhost/?subdomain=pricing'>اضغط هنا</a></p>
    <p>شكراً لاستخدام Vult!</p>
    ";
    
    // In real app, send actual email
    error_log("Expiry notification sent to: $email");
}

function sendTrialExpiredNotification($email) {
    $subject = "انتهت تجربتك المجانية";
    $message = "
    <h2>انتهت تجربتك المجانية</h2>
    <p>مرحباً،</p>
    <p>انتهت تجربتك المجانية في Vult.</p>
    <p>للاستمرار في استخدام المنصة، يرجى الترقية إلى خطة مدفوعة.</p>
    <p>رابط الترقية: <a href='http://vult-saas.localhost/?subdomain=pricing'>اضغط هنا</a></p>
    <p>شكراً لاستخدام Vult!</p>
    ";
    
    // In real app, send actual email
    error_log("Trial expired notification sent to: $email");
}
?>
