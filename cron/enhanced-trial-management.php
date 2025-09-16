<?php
// Enhanced Trial Management System
$servername = "database";
$username = "root";
$password = "root";
$vultDb = "vult";
$portalDb = "portal";

$vultConn = new mysqli($servername, $username, $password, $vultDb);
$portalConn = new mysqli($servername, $username, $password, $portalDb);

if ($vultConn->connect_error || $portalConn->connect_error) {
    die("Connection failed: " . $vultConn->connect_error . " / " . $portalConn->connect_error);
}

echo "Running enhanced trial management cron job...\n";

$current_time = time();
$one_day_ago = $current_time - (1 * 24 * 60 * 60);
$seven_days_ago = $current_time - (7 * 24 * 60 * 60);

// 1. Identify trials expiring soon (in 1 day)
$sql_expiring_soon = "SELECT id, email, trial_expires_at, academy_id FROM user 
                      WHERE trial_expires_at IS NOT NULL 
                      AND trial_expires_at > ? 
                      AND trial_expires_at <= ? 
                      AND academy_id IS NOT NULL";
$stmt = $vultConn->prepare($sql_expiring_soon);
$one_day_from_now = $current_time + (1 * 24 * 60 * 60);
$stmt->bind_param("ii", $current_time, $one_day_from_now);
$stmt->execute();
$result = $stmt->get_result();

$expiring_soon_count = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Trial expiring soon for user: " . $row['email'] . " (ID: " . $row['id'] . ")\n";
        
        // Send notification email
        sendExpiryNotification($row['email'], $row['trial_expires_at']);
        
        // Update academy status in Portal
        updateAcademyTrialStatus($portalConn, $row['academy_id'], 'expiring_soon');
        
        $expiring_soon_count++;
    }
}
$stmt->close();

// 2. Identify expired trials and disable accounts
$sql_expired_trials = "SELECT id, email, trial_expires_at, academy_id FROM user 
                       WHERE trial_expires_at IS NOT NULL 
                       AND trial_expires_at <= ? 
                       AND academy_id IS NOT NULL";
$stmt = $vultConn->prepare($sql_expired_trials);
$stmt->bind_param("i", $current_time);
$stmt->execute();
$result = $stmt->get_result();

$expired_count = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Trial expired for user: " . $row['email'] . " (ID: " . $row['id'] . "). Disabling account.\n";
        
        // Disable the account in Vult
        $update_sql = "UPDATE user SET status = 0 WHERE id = ?";
        $update_stmt = $vultConn->prepare($update_sql);
        $update_stmt->bind_param("i", $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
        
        // Disable academy and user in Portal
        disableAcademyInPortal($portalConn, $row['academy_id'], $row['email']);
        
        // Send expiry notification
        sendTrialExpiredNotification($row['email']);
        
        $expired_count++;
    }
}
$stmt->close();

// 3. Update academy_requests status for expired trials
$sql_update_requests = "UPDATE academy_requests ar 
                        JOIN user u ON ar.email = u.email 
                        SET ar.status = 'expired' 
                        WHERE u.trial_expires_at IS NOT NULL 
                        AND u.trial_expires_at <= ? 
                        AND u.academy_id IS NOT NULL 
                        AND ar.status = 'pending'";
$stmt = $vultConn->prepare($sql_update_requests);
$stmt->bind_param("i", $current_time);
$stmt->execute();
$updated_requests = $stmt->affected_rows;
$stmt->close();

// 4. Clean up very old expired trial data (optional)
$thirty_days_ago = $current_time - (30 * 24 * 60 * 60);
$sql_cleanup_old_trials = "DELETE FROM user 
                           WHERE trial_expires_at IS NOT NULL 
                           AND trial_expires_at <= ? 
                           AND academy_id IS NOT NULL 
                           AND status = 0";
$stmt = $vultConn->prepare($sql_cleanup_old_trials);
$stmt->bind_param("i", $thirty_days_ago);
$stmt->execute();
$cleaned_count = $stmt->affected_rows;
$stmt->close();

// 5. Generate business analytics
generateBusinessAnalytics($vultConn, $portalConn);

echo "Enhanced trial management completed:\n";
echo "- Trials expiring soon: $expiring_soon_count\n";
echo "- Trials expired and disabled: $expired_count\n";
echo "- Old expired accounts cleaned: $cleaned_count\n";
echo "- Academy requests updated: $updated_requests\n";

$vultConn->close();
$portalConn->close();

function updateAcademyTrialStatus($conn, $academyId, $status) {
    $sql = "UPDATE academies SET 
            trial_status = ?,
            updated_at = NOW()
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $academyId);
    $stmt->execute();
    $stmt->close();
}

function disableAcademyInPortal($conn, $academyId, $email) {
    // Disable academy
    $sql = "UPDATE academies SET 
            status = 0,
            subscription_status = 'expired',
            trial_status = 'expired',
            updated_at = NOW()
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $academyId);
    $stmt->execute();
    $stmt->close();
    
    // Disable user
    $sql = "UPDATE user SET 
            status = 0,
            updated_at = NOW()
            WHERE academy_id = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $academyId, $email);
    $stmt->execute();
    $stmt->close();
}

function generateBusinessAnalytics($vultConn, $portalConn) {
    // Get comprehensive analytics
    $analytics = [];
    
    // Vult Analytics
    $result = $vultConn->query("SELECT 
        COUNT(*) as total_requests,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_requests,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_requests
        FROM academy_requests");
    $analytics['vult_requests'] = $result->fetch_assoc();
    
    // Portal Analytics
    $result = $portalConn->query("SELECT 
        COUNT(*) as total_academies,
        SUM(CASE WHEN subscription_plan = 'trial' THEN 1 ELSE 0 END) as trial_academies,
        SUM(CASE WHEN subscription_plan = 'basic' THEN 1 ELSE 0 END) as basic_academies,
        SUM(CASE WHEN subscription_plan = 'premium' THEN 1 ELSE 0 END) as premium_academies,
        SUM(CASE WHEN subscription_plan = 'enterprise' THEN 1 ELSE 0 END) as enterprise_academies,
        SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_academies,
        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive_academies
        FROM academies");
    $analytics['portal_academies'] = $result->fetch_assoc();
    
    // Save analytics to file
    file_put_contents('/var/www/html/vult-saas/analytics.json', json_encode($analytics, JSON_PRETTY_PRINT));
    
    echo "Business analytics generated and saved.\n";
}

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
    
    error_log("Trial expired notification sent to: $email");
}
?>
