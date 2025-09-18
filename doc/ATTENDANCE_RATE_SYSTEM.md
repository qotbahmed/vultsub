# نظام معدل الحضور - Attendance Rate System

## نظرة عامة

تم تطوير نظام شامل لحساب وإدارة معدلات حضور اللاعبين في الأكاديميات الرياضية. يوفر النظام حساب دقيق لمعدلات الحضور بناءً على الحصص المجدولة والحضور الفعلي.

## المكونات الرئيسية

### 1. خدمة حساب معدل الحضور (AttendanceRateService)

**الملف:** `common/services/AttendanceRateService.php`

**الوظائف الرئيسية:**
- `calculatePlayerAttendanceRate()` - حساب معدل حضور لاعب محدد
- `updatePlayerAttendanceRate()` - تحديث معدل حضور لاعب
- `updateAllPlayersAttendanceRates()` - تحديث معدلات جميع اللاعبين
- `getPlayerAttendanceStats()` - الحصول على إحصائيات حضور لاعب
- `getAcademyAttendanceStats()` - الحصول على إحصائيات الأكاديمية
- `markPlayerAttendance()` - تسجيل حضور لاعب

### 2. نموذج حضور اللاعبين (PlayerAttendance)

**الملف:** `common/models/PlayerAttendance.php`

**الوظائف المضافة:**
- `calculateAttendanceRate()` - حساب معدل الحضور
- `getAttendanceStats()` - الحصول على إحصائيات الحضور
- `markAttendance()` - تسجيل الحضور
- `getFormattedAttendanceRate()` - الحصول على معدل الحضور منسق
- `isAttended()` - التحقق من الحضور
- `getAttendanceStatusText()` - الحصول على نص حالة الحضور

### 3. واجهة برمجة التطبيقات (API)

**الملف:** `api/controllers/AttendanceController.php`

**النقاط المتاحة:**
- `GET /api/attendance/player-rate/{playerId}` - معدل حضور لاعب
- `GET /api/attendance/academy-stats/{academyId}` - إحصائيات الأكاديمية
- `POST /api/attendance/mark-attendance` - تسجيل حضور
- `POST /api/attendance/update-rates` - تحديث المعدلات
- `GET /api/attendance/player-history/{playerId}` - تاريخ حضور لاعب

### 4. مهمة تحديث تلقائي (Cron Job)

**الملف:** `cron/update-attendance-rates.php`

**الاستخدام:**
```bash
# تحديث جميع الأكاديميات
php cron/update-attendance-rates.php

# تحديث أكاديمية محددة
php cron/update-attendance-rates.php 1
```

### 5. واجهة اختبار الويب

**الملف:** `web/test-attendance.php`

**الوصول:** `http://vult-sub.localhost/test-attendance.php`

## كيفية العمل

### حساب معدل الحضور

```
معدل الحضور = (عدد الحصص المحضورة / إجمالي الحصص المجدولة) × 100
```

### مثال:
- إجمالي الحصص المجدولة: 20 حصة
- عدد الحصص المحضورة: 17 حصة
- معدل الحضور: (17/20) × 100 = 85%

## قاعدة البيانات

### جدول player_attendance
```sql
CREATE TABLE player_attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    subscription_id INT NOT NULL,
    sub_details_id INT NOT NULL,
    academy_sport_id INT NOT NULL,
    sport_name VARCHAR(255) NOT NULL,
    day INT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    attend_date DATE,
    attend_status INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### جدول players
```sql
ALTER TABLE players ADD COLUMN attendance_rate DECIMAL(5,2) DEFAULT 0.00;
```

## الاستخدام

### 1. تسجيل حضور لاعب

```php
use common\services\AttendanceRateService;

$result = AttendanceRateService::markPlayerAttendance(
    $playerId = 1,
    $scheduleId = 1,
    $subscriptionId = 1,
    $subDetailsId = 1,
    $academySportId = 1,
    $sportName = 'كرة القدم',
    $day = 1,
    $startTime = '09:00:00',
    $endTime = '11:00:00',
    $attendStatus = 1 // 1 = حضر, 0 = غاب
);
```

### 2. حساب معدل حضور لاعب

```php
$attendanceRate = AttendanceRateService::calculatePlayerAttendanceRate($playerId);
echo "معدل الحضور: " . $attendanceRate . "%";
```

### 3. الحصول على إحصائيات الأكاديمية

```php
$stats = AttendanceRateService::getAcademyAttendanceStats($academyId);
echo "متوسط معدل الحضور: " . $stats['average_attendance_rate'] . "%";
```

### 4. تحديث جميع المعدلات

```php
$updated = AttendanceRateService::updateAllPlayersAttendanceRates($academyId);
echo "تم تحديث " . $updated . " لاعب";
```

## التكامل مع النظام

### 1. لوحة التحكم

تم تحديث `DashboardController` لاستخدام النظام الجديد:

```php
// حساب متوسط معدل الحضور
$attendanceStats = AttendanceRateService::getAcademyAttendanceStats($academyId);
$averageRate = $attendanceStats['average_attendance_rate'];
```

### 2. عرض البيانات

يتم عرض معدلات الحضور في:
- لوحة إدارة اللاعبين
- إحصائيات الأكاديمية
- تقارير الأداء

## الاختبار

### 1. اختبار سطر الأوامر

```bash
php test-attendance-rates.php
```

### 2. اختبار الويب

```
http://vult-sub.localhost/test-attendance.php
```

## الصيانة

### 1. تحديث دوري

يُنصح بتشغيل مهمة التحديث التلقائي يومياً:

```bash
# إضافة إلى crontab
0 2 * * * /usr/bin/php /path/to/vult-sub/cron/update-attendance-rates.php
```

### 2. مراقبة الأداء

- مراقبة حجم جدول `player_attendance`
- تنظيف البيانات القديمة دورياً
- فحص دقة الحسابات

## الأمان

- التحقق من صحة البيانات المدخلة
- حماية نقاط API
- تسجيل العمليات المهمة
- التحقق من صلاحيات المستخدم

## الدعم الفني

للحصول على الدعم أو الإبلاغ عن مشاكل:
1. تحقق من ملفات السجل
2. استخدم واجهة الاختبار
3. راجع هذا الدليل

---

**ملاحظة:** هذا النظام متكامل بالكامل مع نظام Vult SaaS ويوفر حساب دقيق ومفصل لمعدلات حضور اللاعبين.
