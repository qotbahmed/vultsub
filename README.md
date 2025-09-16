# Vult SaaS Platform - منصة إدارة الأكاديميات الرياضية

## نظرة عامة

Vult هي منصة SaaS شاملة لإدارة الأكاديميات الرياضية، مصممة خصيصاً للأسواق العربية مع دعم كامل للغة العربية واتجاه النص من اليمين إلى اليسار (RTL).

## المميزات الرئيسية

### 🏆 إدارة الأكاديميات
- **نظام طلبات الأكاديميات**: تسجيل ومراجعة طلبات انضمام الأكاديميات الجديدة
- **لوحة تحكم شاملة**: إدارة كاملة لجميع جوانب الأكاديمية
- **نظام متعدد الفروع**: دعم الأكاديميات متعددة الفروع

### 👥 إدارة اللاعبين
- **ملفات اللاعبين الشاملة**: بيانات شخصية، رياضات، اشتراكات
- **تتبع الحضور**: مراقبة معدلات حضور اللاعبين
- **إدارة الاشتراكات**: تتبع المدفوعات والاشتراكات

### 📊 التقارير والإحصائيات
- **لوحة إدارة متقدمة**: إحصائيات شاملة مع الرسوم البيانية
- **تقارير الأداء**: تتبع تطور اللاعبين والأكاديميات
- **تحليلات مالية**: إدارة الإيرادات والمصروفات

### 🎯 نظام التجربة المجانية
- **7 أيام تجربة مجانية**: بدون الحاجة لبطاقة ائتمان
- **تفعيل تلقائي**: إنشاء حساب وتفعيل التجربة فوراً
- **إشعارات ذكية**: تذكيرات قبل انتهاء التجربة

## التقنيات المستخدمة

- **Backend**: PHP 8.2, Yii2 Framework
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Database**: MySQL 8.0
- **Containerization**: Docker & Docker Compose
- **Charts**: Chart.js
- **Icons**: Font Awesome 6

## التثبيت والتشغيل

### المتطلبات
- Docker & Docker Compose
- Git

### خطوات التثبيت السريع

1. **استنساخ المشروع**
```bash
git clone <repository-url>
cd vult-saas
```

2. **تشغيل النظام**
```bash
./start.sh
```

3. **الوصول للتطبيق**
```
http://vult-saas.localhost
```

### خطوات التثبيت اليدوي

1. **تشغيل Docker**
```bash
docker-compose up -d
```

2. **انتظار قاعدة البيانات**
```bash
sleep 10
```

3. **تشغيل Migrations**
```bash
docker-compose exec webserver php /var/www/html/vult-saas/run_migrations.php
```

4. **التحقق من الحالة**
```bash
./stats.sh
```

## الصفحات المتاحة

### 🏠 الصفحة الرئيسية
- **URL**: `http://vult-saas.localhost/`
- **الوصف**: صفحة الهبوط الرئيسية مع عرض المميزات والأسعار

### 📝 تسجيل الأكاديميات
- **URL**: `http://vult-saas.localhost/?subdomain=signup`
- **الوصف**: صفحة تسجيل الأكاديميات الجديدة

### 🏢 لوحة تحكم الأكاديمية
- **URL**: `http://vult-saas.localhost/?subdomain=academy`
- **الوصف**: لوحة تحكم الأكاديمية مع الإحصائيات

### 📋 إدارة طلبات الأكاديميات
- **URL**: `http://vult-saas.localhost/academy-requests/`
- **الوصف**: إدارة ومراجعة طلبات الأكاديميات

### 👥 إدارة اللاعبين
- **URL**: `http://vult-saas.localhost/players-management/`
- **الوصف**: إدارة بيانات اللاعبين والاشتراكات

### 📊 لوحة الإدارة
- **URL**: `http://vult-saas.localhost/admin-dashboard/`
- **الوصف**: لوحة إدارة شاملة مع الإحصائيات والرسوم البيانية

### 💰 صفحة الأسعار
- **URL**: `http://vult-saas.localhost/?subdomain=pricing`
- **الوصف**: عرض خطط الاشتراك والأسعار

## API Endpoints

### طلبات الأكاديميات
- **GET** `/api/academy-requests.php?endpoint=academy-requests` - جلب جميع الطلبات
- **POST** `/api/academy-requests.php?endpoint=academy-requests` - إنشاء طلب جديد
- **PUT** `/api/academy-requests.php?endpoint=academy-requests&id={id}` - تحديث طلب
- **DELETE** `/api/academy-requests.php?endpoint=academy-requests&id={id}` - حذف طلب

### اللاعبين
- **GET** `/api/academy-requests.php?endpoint=players` - جلب جميع اللاعبين
- **POST** `/api/academy-requests.php?endpoint=players` - إنشاء لاعب جديد
- **PUT** `/api/academy-requests.php?endpoint=players&id={id}` - تحديث لاعب
- **DELETE** `/api/academy-requests.php?endpoint=players&id={id}` - حذف لاعب

## قاعدة البيانات

### الجداول الرئيسية

#### academy_requests
```sql
- id (Primary Key)
- academy_name (اسم الأكاديمية)
- manager_name (اسم المدير)
- email (البريد الإلكتروني)
- phone (رقم الهاتف)
- address (العنوان)
- city (المدينة)
- branches_count (عدد الفروع)
- sports (الرياضات)
- description (الوصف)
- status (الحالة: pending/approved/rejected)
- requested_at (تاريخ الطلب)
- approved_at (تاريخ الموافقة)
- rejected_at (تاريخ الرفض)
```

#### players
```sql
- id (Primary Key)
- name (الاسم)
- nationality (الجنسية)
- id_number (رقم الهوية)
- phone (رقم الهاتف)
- address (العنوان)
- dob (تاريخ الميلاد)
- academy_id (معرف الأكاديمية)
- sport (الرياضة)
- status (الحالة: active/inactive/suspended)
- attendance_rate (معدل الحضور)
- paid (المبلغ المدفوع)
```

#### user (محدث)
```sql
- id (Primary Key)
- username (اسم المستخدم)
- email (البريد الإلكتروني)
- trial_started_at (بداية التجربة)
- trial_expires_at (انتهاء التجربة)
- academy_id (معرف الأكاديمية)
- ... (حقول أخرى)
```

## الأدوات المساعدة

### ملفات التشغيل
- `./start.sh` - تشغيل النظام مع migrations
- `./stop.sh` - إيقاف النظام
- `./stats.sh` - عرض الإحصائيات

### ملفات Migration
- `run_migrations.php` - تشغيل migrations يدوياً
- `console/migrations/` - ملفات Yii2 migrations

## المميزات التقنية

### 🎨 التصميم
- **تصميم متجاوب**: يعمل على جميع الأجهزة
- **دعم RTL كامل**: للغة العربية
- **ألوان رياضية**: تصميم يحاكي الروح الرياضية
- **رسوم بيانية تفاعلية**: باستخدام Chart.js

### 🔒 الأمان
- **حماية من SQL Injection**: استخدام Prepared Statements
- **تشفير البيانات**: حماية البيانات الحساسة
- **CORS Support**: دعم الطلبات من مصادر مختلفة

### ⚡ الأداء
- **تحميل سريع**: تحسين الصور والملفات
- **Caching**: تخزين مؤقت للبيانات
- **Lazy Loading**: تحميل تدريجي للمحتوى

## استكشاف الأخطاء

### مشاكل شائعة

1. **خطأ "Could not open input file: yii"**
   ```bash
   # الحل: استخدم run_migrations.php بدلاً من yii
   docker-compose exec webserver php /var/www/html/vult-saas/run_migrations.php
   ```

2. **خطأ "Database connection failed"**
   ```bash
   # الحل: تأكد من تشغيل قاعدة البيانات
   docker-compose ps
   docker-compose restart database
   ```

3. **خطأ "Permission denied"**
   ```bash
   # الحل: تأكد من الصلاحيات
   chmod +x start.sh stop.sh stats.sh
   ```

### سجلات النظام
```bash
# عرض سجلات Docker
docker-compose logs

# عرض سجلات قاعدة البيانات
docker-compose logs database

# عرض سجلات الخادم
docker-compose logs webserver
```

## التطوير المستقبلي

### المرحلة القادمة
- [ ] نظام الدفع الإلكتروني (Paylink/Stripe)
- [ ] تطبيق موبايل للاعبين
- [ ] نظام الإشعارات
- [ ] تقارير PDF
- [ ] نظام الشهادات

### المميزات المتقدمة
- [ ] الذكاء الاصطناعي لتحليل الأداء
- [ ] نظام المنافسات والبطولات
- [ ] تكامل مع أجهزة اللياقة البدنية
- [ ] نظام النقاط والمكافآت

## المساهمة

نرحب بمساهماتكم! يرجى اتباع الخطوات التالية:

1. Fork المشروع
2. إنشاء فرع للميزة الجديدة
3. Commit التغييرات
4. Push للفرع
5. إنشاء Pull Request

## الترخيص

هذا المشروع مرخص تحت رخصة MIT - راجع ملف [LICENSE](LICENSE) للتفاصيل.

## الدعم

للحصول على الدعم أو الإبلاغ عن مشاكل:
- 📧 البريد الإلكتروني: support@vult.com
- 📱 الهاتف: +966 50 123 4567
- 🌐 الموقع: https://vult.com

---

**Vult** - أطلق إمكانيات أكاديميتك الرياضية! 🏆
