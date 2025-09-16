<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة - Vult</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .header-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem 0;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        
        .stats-card.danger {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
        }
        
        .stats-card.info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .admin-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            color: inherit;
        }
        
        .recent-activity {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 font-weight-bold mb-3">
                        <i class="fas fa-tachometer-alt me-3"></i>لوحة الإدارة
                    </h1>
                    <p class="lead mb-0">نظرة شاملة على النظام والإحصائيات</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="../?subdomain=main" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>العودة للرئيسية
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <!-- Statistics Cards -->
        <div class="row" id="statsCards">
            <div class="col-md-3">
                <div class="stats-card success">
                    <h3 class="mb-1" id="totalAcademies">-</h3>
                    <small>إجمالي الأكاديميات</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <h3 class="mb-1" id="totalPlayers">-</h3>
                    <small>إجمالي اللاعبين</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <h3 class="mb-1" id="pendingRequests">-</h3>
                    <small>طلبات في الانتظار</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card danger">
                    <h3 class="mb-1" id="rejectedRequests">-</h3>
                    <small>طلبات مرفوضة</small>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">إحصائيات الطلبات الشهرية</h5>
                    <canvas id="requestsChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">توزيع الرياضات</h5>
                    <canvas id="sportsChart" width="300" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Admin Tools Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="recent-activity">
                    <h5 class="fw-bold mb-4">النشاط الأخير</h5>
                    <div id="recentActivity">
                        <div class="loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="mt-3">جاري تحميل النشاط...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-card">
                    <h5 class="fw-bold mb-4">أدوات الإدارة</h5>
                    <div class="d-grid gap-2">
                        <a href="../academy-requests/" class="btn btn-outline-primary">
                            <i class="fas fa-clipboard-list me-2"></i>إدارة طلبات الأكاديميات
                        </a>
                        <a href="../players-management/" class="btn btn-outline-success">
                            <i class="fas fa-users me-2"></i>إدارة اللاعبين
                        </a>
                        <a href="../?subdomain=academy" class="btn btn-outline-info">
                            <i class="fas fa-tachometer-alt me-2"></i>لوحة تحكم الأكاديمية
                        </a>
                        <a href="../?subdomain=pricing" class="btn btn-outline-warning">
                            <i class="fas fa-tag me-2"></i>إدارة الأسعار
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">أداء النظام</h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mb-3">
                                <h4 class="text-success" id="systemUptime">99.9%</h4>
                                <small class="text-muted">وقت التشغيل</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <h4 class="text-info" id="responseTime">120ms</h4>
                                <small class="text-muted">زمن الاستجابة</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <h4 class="text-warning" id="activeUsers">1,247</h4>
                                <small class="text-muted">المستخدمين النشطين</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">إحصائيات سريعة</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary" id="monthlyRevenue">45,600</h4>
                                <small class="text-muted">الإيرادات الشهرية (ريال)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success" id="growthRate">+12.5%</h4>
                                <small class="text-muted">معدل النمو</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let requestsChart, sportsChart;
        
        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
        });
        
        async function loadDashboardData() {
            try {
                // Load academy requests
                const requestsResponse = await fetch('../api/academy-requests.php?endpoint=academy-requests');
                const requestsData = await requestsResponse.json();
                
                // Load players
                const playersResponse = await fetch('../api/academy-requests.php?endpoint=players');
                const playersData = await playersResponse.json();
                
                if (requestsData.success && playersData.success) {
                    updateStats(requestsData.data, playersData.data);
                    createCharts(requestsData.data, playersData.data);
                    updateRecentActivity(requestsData.data);
                } else {
                    showError('حدث خطأ في تحميل البيانات');
                }
            } catch (error) {
                showError('حدث خطأ في الاتصال: ' + error.message);
            }
        }
        
        function updateStats(requests, players) {
            const totalAcademies = requests.filter(r => r.status === 'approved').length;
            const totalPlayers = players.length;
            const pendingRequests = requests.filter(r => r.status === 'pending').length;
            const rejectedRequests = requests.filter(r => r.status === 'rejected').length;
            
            document.getElementById('totalAcademies').textContent = totalAcademies;
            document.getElementById('totalPlayers').textContent = totalPlayers;
            document.getElementById('pendingRequests').textContent = pendingRequests;
            document.getElementById('rejectedRequests').textContent = rejectedRequests;
        }
        
        function createCharts(requests, players) {
            // Requests Chart
            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            requestsChart = new Chart(requestsCtx, {
                type: 'line',
                data: {
                    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر'],
                    datasets: [{
                        label: 'طلبات جديدة',
                        data: [12, 19, 15, 25, 22, 18, 30, 28, 24],
                        borderColor: '#ff6b35',
                        backgroundColor: 'rgba(255, 107, 53, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'طلبات موافق عليها',
                        data: [8, 15, 12, 20, 18, 14, 25, 22, 19],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Sports Chart
            const sportsCtx = document.getElementById('sportsChart').getContext('2d');
            const sportsData = {};
            players.forEach(player => {
                if (player.sport) {
                    sportsData[player.sport] = (sportsData[player.sport] || 0) + 1;
                }
            });
            
            sportsChart = new Chart(sportsCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(sportsData),
                    datasets: [{
                        data: Object.values(sportsData),
                        backgroundColor: [
                            '#ff6b35',
                            '#28a745',
                            '#17a2b8',
                            '#ffc107',
                            '#dc3545',
                            '#6f42c1'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }
        
        function updateRecentActivity(requests) {
            const recentActivity = document.getElementById('recentActivity');
            const recentRequests = requests.slice(0, 5);
            
            if (recentRequests.length === 0) {
                recentActivity.innerHTML = '<p class="text-muted text-center">لا يوجد نشاط حديث</p>';
                return;
            }
            
            recentActivity.innerHTML = recentRequests.map(request => `
                <div class="activity-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-clipboard-list text-primary fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${request.academy_name}</h6>
                            <p class="text-muted mb-1">${request.manager_name} - ${request.email}</p>
                            <small class="text-muted">${formatDate(request.requested_at)}</small>
                        </div>
                        <div>
                            <span class="badge bg-${getStatusColor(request.status)}">${getStatusText(request.status)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        function getStatusText(status) {
            const statusMap = {
                'pending': 'في الانتظار',
                'approved': 'موافق عليها',
                'rejected': 'مرفوضة'
            };
            return statusMap[status] || status;
        }
        
        function getStatusColor(status) {
            const colorMap = {
                'pending': 'warning',
                'approved': 'success',
                'rejected': 'danger'
            };
            return colorMap[status] || 'secondary';
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('ar-SA');
        }
        
        function showError(message) {
            const recentActivity = document.getElementById('recentActivity');
            recentActivity.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">حدث خطأ</h5>
                    <p class="text-muted">${message}</p>
                    <button class="btn btn-primary" onclick="loadDashboardData()">إعادة المحاولة</button>
                </div>
            `;
        }
    </script>
</body>
</html>
