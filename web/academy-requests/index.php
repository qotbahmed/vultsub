<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة طلبات الأكاديميات - Vult</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .header-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem 0;
        }
        
        .request-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .request-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .btn-action {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-approve {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
        }
        
        .btn-approve:hover {
            background: linear-gradient(45deg, #218838, #1e7e34);
            transform: translateY(-2px);
        }
        
        .btn-reject {
            background: linear-gradient(45deg, #dc3545, #e74c3c);
            border: none;
            color: white;
        }
        
        .btn-reject:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
            transform: translateY(-2px);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .filter-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
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
                        <i class="fas fa-clipboard-list me-3"></i>إدارة طلبات الأكاديميات
                    </h1>
                    <p class="lead mb-0">إدارة ومراجعة طلبات انضمام الأكاديميات الجديدة</p>
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
                <div class="stats-card">
                    <h3 class="mb-1" id="totalRequests">-</h3>
                    <small>إجمالي الطلبات</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1" id="pendingRequests">-</h3>
                    <small>في الانتظار</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1" id="approvedRequests">-</h3>
                    <small>موافق عليها</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1" id="rejectedRequests">-</h3>
                    <small>مرفوضة</small>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label fw-bold">حالة الطلب:</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">جميع الطلبات</option>
                        <option value="pending">في الانتظار</option>
                        <option value="approved">موافق عليها</option>
                        <option value="rejected">مرفوضة</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">تاريخ الطلب:</label>
                    <input type="date" class="form-control" id="dateFilter">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">البحث:</label>
                    <input type="text" class="form-control" placeholder="ابحث عن أكاديمية..." id="searchInput">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">&nbsp;</label>
                    <button class="btn btn-primary w-100" onclick="loadRequests()">
                        <i class="fas fa-search me-2"></i>بحث
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div class="loading" id="loadingDiv">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-3">جاري تحميل الطلبات...</p>
        </div>

        <!-- Requests List -->
        <div class="row" id="requestsList">
            <!-- Requests will be loaded here -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allRequests = [];
        
        // Load requests on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadRequests();
        });
        
        async function loadRequests() {
            const loadingDiv = document.getElementById('loadingDiv');
            const requestsList = document.getElementById('requestsList');
            
            loadingDiv.style.display = 'block';
            requestsList.innerHTML = '';
            
            try {
                const response = await fetch('../api/academy-requests.php');
                const data = await response.json();
                
                if (data.success) {
                    allRequests = data.data;
                    displayRequests(allRequests);
                    updateStats(allRequests);
                } else {
                    showError('حدث خطأ في تحميل الطلبات: ' + data.error);
                }
            } catch (error) {
                showError('حدث خطأ في الاتصال: ' + error.message);
            } finally {
                loadingDiv.style.display = 'none';
            }
        }
        
        function displayRequests(requests) {
            const requestsList = document.getElementById('requestsList');
            
            if (requests.length === 0) {
                requestsList.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد طلبات</h5>
                        <p class="text-muted">لم يتم العثور على أي طلبات تطابق المعايير المحددة</p>
                    </div>
                `;
                return;
            }
            
            requestsList.innerHTML = requests.map(request => `
                <div class="col-md-6 col-lg-4 request-item" data-status="${request.status}">
                    <div class="card request-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">${request.academy_name}</h5>
                                <span class="status-badge status-${request.status}">
                                    ${getStatusText(request.status)}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted mb-2">
                                    <i class="fas fa-user me-2"></i>${request.manager_name}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-envelope me-2"></i>${request.email}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-phone me-2"></i>${request.phone}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>${request.address || 'غير محدد'}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold">الرياضات:</h6>
                                <div class="d-flex flex-wrap">
                                    ${request.sports ? request.sports.split(',').map(sport => 
                                        `<span class="badge bg-primary me-1 mb-1">${sport.trim()}</span>`
                                    ).join('') : '<span class="text-muted">غير محدد</span>'}
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold">عدد الفروع:</h6>
                                <span class="text-primary fw-bold">${request.branches_count} فرع</span>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold">تاريخ الطلب:</h6>
                                <span class="text-muted">${formatDate(request.requested_at)}</span>
                            </div>
                            
                            ${request.status === 'pending' ? `
                                <div class="d-flex gap-2">
                                    <button class="btn btn-approve btn-action flex-fill" onclick="approveRequest(${request.id})">
                                        <i class="fas fa-check me-2"></i>موافقة
                                    </button>
                                    <button class="btn btn-reject btn-action flex-fill" onclick="rejectRequest(${request.id})">
                                        <i class="fas fa-times me-2"></i>رفض
                                    </button>
                                </div>
                            ` : `
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-action flex-fill" onclick="viewRequest(${request.id})">
                                        <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                    </button>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        function updateStats(requests) {
            const total = requests.length;
            const pending = requests.filter(r => r.status === 'pending').length;
            const approved = requests.filter(r => r.status === 'approved').length;
            const rejected = requests.filter(r => r.status === 'rejected').length;
            
            document.getElementById('totalRequests').textContent = total;
            document.getElementById('pendingRequests').textContent = pending;
            document.getElementById('approvedRequests').textContent = approved;
            document.getElementById('rejectedRequests').textContent = rejected;
        }
        
        function getStatusText(status) {
            const statusMap = {
                'pending': 'في الانتظار',
                'approved': 'موافق عليها',
                'rejected': 'مرفوضة'
            };
            return statusMap[status] || status;
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('ar-SA');
        }
        
        async function approveRequest(requestId) {
            if (confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')) {
                try {
                    const response = await fetch(`../api/academy-requests.php/academy-requests/${requestId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: 'approved',
                            notes: 'تمت الموافقة على الطلب'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('تم الموافقة على الطلب بنجاح!');
                        loadRequests();
                    } else {
                        alert('حدث خطأ: ' + data.error);
                    }
                } catch (error) {
                    alert('حدث خطأ في الاتصال: ' + error.message);
                }
            }
        }
        
        async function rejectRequest(requestId) {
            if (confirm('هل أنت متأكد من رفض هذا الطلب؟')) {
                try {
                    const response = await fetch(`../api/academy-requests.php/academy-requests/${requestId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: 'rejected',
                            notes: 'تم رفض الطلب'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('تم رفض الطلب!');
                        loadRequests();
                    } else {
                        alert('حدث خطأ: ' + data.error);
                    }
                } catch (error) {
                    alert('حدث خطأ في الاتصال: ' + error.message);
                }
            }
        }
        
        function viewRequest(requestId) {
            const request = allRequests.find(r => r.id == requestId);
            if (request) {
                alert(`تفاصيل الطلب:\n\nالأكاديمية: ${request.academy_name}\nالمدير: ${request.manager_name}\nالبريد: ${request.email}\nالهاتف: ${request.phone}\nالرياضات: ${request.sports}\nعدد الفروع: ${request.branches_count}\nالوصف: ${request.description || 'غير محدد'}`);
            }
        }
        
        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchInput = document.getElementById('searchInput').value;
            
            let filteredRequests = allRequests;
            
            if (statusFilter) {
                filteredRequests = filteredRequests.filter(r => r.status === statusFilter);
            }
            
            if (searchInput) {
                filteredRequests = filteredRequests.filter(r => 
                    r.academy_name.toLowerCase().includes(searchInput.toLowerCase()) ||
                    r.manager_name.toLowerCase().includes(searchInput.toLowerCase()) ||
                    r.email.toLowerCase().includes(searchInput.toLowerCase())
                );
            }
            
            displayRequests(filteredRequests);
        }
        
        function showError(message) {
            const requestsList = document.getElementById('requestsList');
            requestsList.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">حدث خطأ</h5>
                    <p class="text-muted">${message}</p>
                    <button class="btn btn-primary" onclick="loadRequests()">إعادة المحاولة</button>
                </div>
            `;
        }
        
        // Auto-apply filters on input change
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('searchInput').addEventListener('input', applyFilters);
    </script>
</body>
</html>
