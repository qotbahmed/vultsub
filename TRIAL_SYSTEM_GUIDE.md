# دليل نظام التجربة المجانية - Vult SaaS Platform

## 🎯 نظرة عامة

نظام التجربة المجانية في Vult يسمح للأكاديميات الرياضية بتجربة المنصة لمدة 7 أيام مجاناً قبل الاشتراك في إحدى الخطط المدفوعة.

## 🔄 تدفق العمل (Workflow)

### 1. **مرحلة التسجيل**
```
المستخدم → صفحة التسجيل → ملء البيانات → إرسال الطلب → إنشاء حساب تجريبي
```

### 2. **مرحلة المراجعة**
```
الطلب → جدول academy_requests → مراجعة الإدارة → الموافقة/الرفض
```

### 3. **مرحلة التجربة**
```
الموافقة → إنشاء أكاديمية في Portal → تفعيل التجربة → إرسال إيميل ترحيبي
```

### 4. **مرحلة الترقية**
```
انتهاء التجربة → إشعارات → صفحة الترقية → الدفع → تفعيل الاشتراك
```

## 📊 قاعدة البيانات

### الجداول المستخدمة

#### 1. **academy_requests** - طلبات الأكاديميات
```sql
CREATE TABLE `academy_requests` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `academy_name` varchar(255) NOT NULL,
    `manager_name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `address` text,
    `city` varchar(100) DEFAULT NULL,
    `branches_count` int(11) DEFAULT 1,
    `sports` text,
    `description` text,
    `status` enum('pending','approved','rejected') DEFAULT 'pending',
    `requested_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `approved_at` timestamp NULL DEFAULT NULL,
    `rejected_at` timestamp NULL DEFAULT NULL,
    `notes` text,
    `created_by` int(11) DEFAULT NULL,
    `updated_by` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
);
```

#### 2. **user** - المستخدمين (محدث)
```sql
ALTER TABLE `user` ADD COLUMN `trial_started_at` INT NULL;
ALTER TABLE `user` ADD COLUMN `trial_expires_at` INT NULL;
ALTER TABLE `user` ADD COLUMN `academy_id` INT NULL;
```

#### 3. **academies** - الأكاديميات (من Portal)
```sql
-- يتم إنشاء الأكاديمية هنا عند الموافقة على الطلب
```

## 🛠️ API Endpoints

### 1. **بدء التجربة**
```
POST /api/trial-management.php?endpoint=start-trial
```

**Request Body:**
```json
{
    "academy_name": "أكاديمية النجوم الرياضية",
    "manager_name": "أحمد محمد العلي",
    "email": "ahmed@stars-sports.com",
    "phone": "+966501234567",
    "address": "شارع الملك فهد، الرياض",
    "city": "الرياض",
    "branches_count": 3,
    "sports": "كرة القدم,كرة السلة,السباحة",
    "description": "أكاديمية متخصصة في تدريب الشباب"
}
```

**Response:**
```json
{
    "success": true,
    "message": "تم بدء التجربة المجانية بنجاح",
    "data": {
        "request_id": 1,
        "user_id": 1,
        "trial_start": 1694784000,
        "trial_end": 1695388800,
        "trial_days_left": 7
    }
}
```

### 2. **فحص حالة التجربة**
```
GET /api/trial-management.php?endpoint=trial-status&email=user@example.com
```

**Response:**
```json
{
    "success": true,
    "data": {
        "is_trial_active": true,
        "trial_days_left": 5,
        "trial_start": 1694784000,
        "trial_end": 1695388800,
        "academy_name": "أكاديمية النجوم الرياضية",
        "request_status": "approved"
    }
}
```

### 3. **الموافقة على الأكاديمية**
```
POST /api/trial-management.php?endpoint=approve-academy
```

**Request Body:**
```json
{
    "request_id": 1
}
```

**Response:**
```json
{
    "success": true,
    "message": "تم الموافقة على الأكاديمية وإنشاؤها في النظام",
    "data": {
        "academy_id": 1,
        "academy_name": "أكاديمية النجوم الرياضية"
    }
}
```

## �� الصفحات المتاحة

### 1. **صفحة التسجيل**
- **URL**: `http://vult-saas.localhost/?subdomain=signup`
- **الوصف**: صفحة تسجيل الأكاديميات الجديدة
- **المميزات**:
  - نموذج تسجيل شامل
  - اختيار الرياضات
  - التحقق من البيانات
  - إرسال طلب التجربة

### 2. **لوحة تحكم التجربة**
- **URL**: `http://vult-saas.localhost/trial-dashboard/?email=user@example.com`
- **الوصف**: لوحة تحكم للأكاديميات في فترة التجربة
- **المميزات**:
  - عرض حالة التجربة
  - عداد الأيام المتبقية
  - الوصول للمميزات الأساسية
  - خيارات الترقية

### 3. **إدارة طلبات الأكاديميات**
- **URL**: `http://vult-saas.localhost/academy-requests/`
- **الوصف**: صفحة إدارة الطلبات للمديرين
- **المميزات**:
  - عرض جميع الطلبات
  - مراجعة البيانات
  - الموافقة/الرفض
  - إضافة ملاحظات

## ⏰ نظام Cron Jobs

### 1. **إدارة انتهاء التجارب**
```bash
# تشغيل يومي في الساعة 9:00 صباحاً
0 9 * * * docker-compose exec webserver php /var/www/html/vult-saas/cron/trial-management.php
```

### 2. **المهام المنجزة**
- ✅ فحص التجارب المنتهية خلال يومين
- ✅ إرسال إشعارات التحذير
- ✅ تعطيل الحسابات المنتهية
- ✅ تنظيف البيانات القديمة

## 📧 نظام الإشعارات

### 1. **إيميل الترحيب**
```
الموضوع: مرحباً بك في Vult - بدء تجربتك المجانية
المحتوى:
- ترحيب بالأكاديمية
- معلومات التجربة
- رابط لوحة التحكم
- تعليمات الاستخدام
```

### 2. **إشعار انتهاء التجربة**
```
الموضوع: تنبيه: تجربتك المجانية تنتهي قريباً
المحتوى:
- عدد الأيام المتبقية
- رابط الترقية
- مميزات الاشتراك
```

### 3. **إشعار انتهاء التجربة**
```
الموضوع: انتهت تجربتك المجانية
المحتوى:
- تأكيد انتهاء التجربة
- رابط الترقية
- ضمان حفظ البيانات
```

## 🔐 الأمان والخصوصية

### 1. **حماية البيانات**
- تشفير كلمات المرور
- حماية من SQL Injection
- التحقق من صحة البيانات
- CORS Support

### 2. **إدارة الجلسات**
- حفظ البريد الإلكتروني في localStorage
- التحقق من صحة الجلسات
- انتهاء تلقائي للجلسات

### 3. **النسخ الاحتياطي**
- نسخ احتياطي يومي للبيانات
- حفظ سجلات العمليات
- استرداد البيانات عند الحاجة

## 📈 الإحصائيات والمراقبة

### 1. **إحصائيات التجارب**
- عدد الطلبات الجديدة
- معدل التحويل للاشتراك
- متوسط مدة التجربة
- أسباب الرفض

### 2. **مراقبة الأداء**
- وقت الاستجابة
- معدل الأخطاء
- استخدام الموارد
- سجلات النظام

## 🚀 التطوير المستقبلي

### 1. **مميزات قادمة**
- [ ] نظام الدفع الإلكتروني
- [ ] تخصيص فترة التجربة
- [ ] إشعارات push
- [ ] تطبيق موبايل للتجربة

### 2. **تحسينات**
- [ ] تحسين واجهة المستخدم
- [ ] إضافة المزيد من الرياضات
- [ ] نظام التقييمات
- [ ] دعم متعدد اللغات

## 🛠️ استكشاف الأخطاء

### 1. **مشاكل شائعة**

#### خطأ في الاتصال بقاعدة البيانات
```bash
# الحل: تأكد من تشغيل قاعدة البيانات
docker-compose ps
docker-compose restart database
```

#### خطأ في API
```bash
# الحل: تحقق من logs
docker-compose logs webserver
```

#### مشكلة في الإيميلات
```bash
# الحل: تحقق من إعدادات SMTP
# أو استخدم نظام إيميل خارجي
```

### 2. **أدوات التشخيص**
```bash
# فحص حالة النظام
./stats.sh

# تشغيل migration
docker-compose exec webserver php /var/www/html/vult-saas/run_migrations.php

# تشغيل cron job يدوياً
docker-compose exec webserver php /var/www/html/vult-saas/cron/trial-management.php
```

## 📞 الدعم والمساعدة

### للمطورين
- **GitHub**: [رابط المستودع]
- **Documentation**: README.md
- **API Docs**: في الكود

### للمستخدمين
- **الدعم الفني**: support@vult.com
- **الهاتف**: +966 50 123 4567
- **الموقع**: https://vult.com

---

**Vult SaaS Platform** - نظام تجربة مجانية متطور للأكاديميات الرياضية 🏆

*تم التطوير بواسطة فريق Vult - 2024*
