#!/bin/bash

echo "🛑 إيقاف منصة Vult SaaS..."

# إيقاف الحاويات
echo "📦 إيقاف الحاويات..."
docker-compose down

echo "✅ تم إيقاف جميع الخدمات بنجاح!"
echo ""
echo "💡 لإعادة التشغيل، استخدم: ./start.sh"
