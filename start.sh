#!/bin/bash

echo "�� بدء تشغيل منصة Vult SaaS..."

# التحقق من وجود Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker غير مثبت. يرجى تثبيت Docker أولاً."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose غير مثبت. يرجى تثبيت Docker Compose أولاً."
    exit 1
fi

# تشغيل الحاويات
echo "📦 تشغيل الحاويات..."
docker-compose up -d

# انتظار تشغيل قاعدة البيانات
echo "⏳ انتظار تشغيل قاعدة البيانات..."
sleep 10

# تشغيل migrations
echo "🗄️ تشغيل migrations..."
docker-compose exec webserver php /var/www/html/vult-saas/run_migrations.php

# التحقق من حالة الخدمات
echo "🔍 التحقق من حالة الخدمات..."
docker-compose ps

echo ""
echo "✅ تم تشغيل منصة Vult بنجاح!"
echo ""
echo "🌐 الروابط المتاحة:"
echo "   الرئيسية: http://vult-saas.localhost/"
echo "   التسجيل: http://vult-saas.localhost/?subdomain=signup"
echo "   لوحة الأكاديمية: http://vult-saas.localhost/?subdomain=academy"
echo "   إدارة الطلبات: http://vult-saas.localhost/academy-requests/"
echo "   إدارة اللاعبين: http://vult-saas.localhost/players-management/"
echo "   لوحة الإدارة: http://vult-saas.localhost/admin-dashboard/"
echo "   الأسعار: http://vult-saas.localhost/?subdomain=pricing"
echo ""
echo "📚 للمزيد من المعلومات، راجع ملف README.md"
echo ""
echo "🛑 لإيقاف الخدمات، استخدم: docker-compose down"
