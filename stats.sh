#!/bin/bash

echo "�� إحصائيات منصة Vult SaaS"
echo "================================"

# التحقق من حالة الخدمات
echo "�� حالة الخدمات:"
docker-compose ps

echo ""

# إحصائيات قاعدة البيانات
echo "🗄️ إحصائيات قاعدة البيانات:"
echo "طلبات الأكاديميات:"
docker-compose exec -T database mysql -u root -proot -e "USE vult; SELECT status, COUNT(*) as count FROM academy_requests GROUP BY status;" 2>/dev/null || echo "❌ لا يمكن الاتصال بقاعدة البيانات"

echo ""
echo "اللاعبين:"
docker-compose exec -T database mysql -u root -proot -e "USE vult; SELECT status, COUNT(*) as count FROM players GROUP BY status;" 2>/dev/null || echo "❌ لا يمكن الاتصال بقاعدة البيانات"

echo ""
echo "إجمالي الأكاديميات:"
docker-compose exec -T database mysql -u root -proot -e "USE vult; SELECT COUNT(*) as total FROM academies;" 2>/dev/null || echo "❌ لا يمكن الاتصال بقاعدة البيانات"

echo ""
echo "🌐 الروابط المتاحة:"
echo "   الرئيسية: http://vult-saas.localhost/"
echo "   إدارة الطلبات: http://vult-saas.localhost/academy-requests/"
echo "   إدارة اللاعبين: http://vult-saas.localhost/players-management/"
echo "   لوحة الإدارة: http://vult-saas.localhost/admin-dashboard/"
