<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة اللاعبين - Vult</title>
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
        
        .player-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .player-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .player-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 11px;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-suspended {
            background: #fff3cd;
            color: #856404;
        }
        
        .btn-action {
            border-radius: 20px;
            padding: 6px 16px;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            color: white;
        }
        
        .btn-edit:hover {
            background: linear-gradient(45deg, #0056b3, #004085);
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background: linear-gradient(45deg, #dc3545, #c82333);
            border: none;
            color: white;
        }
        
        .btn-delete:hover {
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
        
        .modal-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.25);
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
                        <i class="fas fa-users me-3"></i>إدارة اللاعبين
                    </h1>
                    <p class="lead mb-0">إدارة بيانات اللاعبين والاشتراكات</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light btn-lg me-2" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                        <i class="fas fa-plus me-2"></i>إضافة لاعب جديد
                    </button>
                    <a href="../?subdomain=academy" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>العودة للداشبورد
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
                    <h3 class="mb-1" id="totalPlayers">-</h3>
                    <small>إجمالي اللاعبين</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1" id="activePlayers">-</h3>
                    <small>نشط</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1" id="suspendedPlayers">-</h3>
                    <small>معلق</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1" id="inactivePlayers">-</h3>
                    <small>غير نشط</small>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label fw-bold">الحالة:</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">جميع اللاعبين</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="suspended">معلق</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">الرياضة:</label>
                    <select class="form-select" id="sportFilter">
                        <option value="">جميع الرياضات</option>
                        <option value="كرة القدم">كرة القدم</option>
                        <option value="كرة السلة">كرة السلة</option>
                        <option value="السباحة">السباحة</option>
                        <option value="التنس">التنس</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">البحث:</label>
                    <input type="text" class="form-control" placeholder="ابحث عن لاعب..." id="searchInput">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">&nbsp;</label>
                    <button class="btn btn-primary w-100" onclick="loadPlayers()">
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
            <p class="mt-3">جاري تحميل اللاعبين...</p>
        </div>

        <!-- Players List -->
        <div class="row" id="playersList">
            <!-- Players will be loaded here -->
        </div>
    </div>

    <!-- Add Player Modal -->
    <div class="modal fade" id="addPlayerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>إضافة لاعب جديد
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPlayerForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">الاسم الكامل *</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">تاريخ الميلاد *</label>
                                    <input type="date" class="form-control" name="dob" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">رقم الهاتف *</label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">رقم الهوية *</label>
                                    <input type="text" class="form-control" name="id_number" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">الجنسية *</label>
                                    <select class="form-select" name="nationality" required>
                                        <option value="">اختر الجنسية</option>
                                        <option value="سعودي">سعودي</option>
                                        <option value="مصري">مصري</option>
                                        <option value="سوري">سوري</option>
                                        <option value="أردني">أردني</option>
                                        <option value="أخرى">أخرى</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">الرياضة *</label>
                                    <select class="form-select" name="sport" required>
                                        <option value="">اختر الرياضة</option>
                                        <option value="كرة القدم">كرة القدم</option>
                                        <option value="كرة السلة">كرة السلة</option>
                                        <option value="السباحة">السباحة</option>
                                        <option value="التنس">التنس</option>
                                        <option value="الجمباز">الجمباز</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">العنوان</label>
                            <textarea class="form-control" name="address" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="savePlayer()">
                        <i class="fas fa-save me-2"></i>حفظ اللاعب
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allPlayers = [];
        
        // Load players on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPlayers();
        });
        
        async function loadPlayers() {
            const loadingDiv = document.getElementById('loadingDiv');
            const playersList = document.getElementById('playersList');
            
            loadingDiv.style.display = 'block';
            playersList.innerHTML = '';
            
            try {
                const response = await fetch('../api/academy-requests.php?endpoint=players');
                const data = await response.json();
                
                if (data.success) {
                    allPlayers = data.data;
                    displayPlayers(allPlayers);
                    updateStats(allPlayers);
                } else {
                    showError('حدث خطأ في تحميل اللاعبين: ' + data.error);
                }
            } catch (error) {
                showError('حدث خطأ في الاتصال: ' + error.message);
            } finally {
                loadingDiv.style.display = 'none';
            }
        }
        
        function displayPlayers(players) {
            const playersList = document.getElementById('playersList');
            
            if (players.length === 0) {
                playersList.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا يوجد لاعبين</h5>
                        <p class="text-muted">لم يتم العثور على أي لاعبين يطابقون المعايير المحددة</p>
                    </div>
                `;
                return;
            }
            
            playersList.innerHTML = players.map(player => `
                <div class="col-md-6 col-lg-4 player-item" data-status="${player.status}" data-sport="${player.sport || ''}">
                    <div class="card player-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://via.placeholder.com/80x80/1e3c72/ffffff?text=${player.name.charAt(0)}" class="player-avatar me-3" alt="Player Avatar">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">${player.name}</h5>
                                    <p class="text-muted mb-0">${player.sport || 'غير محدد'}</p>
                                    <span class="status-badge status-${player.status}">${getStatusText(player.status)}</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar me-2"></i>العمر: ${calculateAge(player.dob)} سنة
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-phone me-2"></i>${player.phone || 'غير محدد'}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>${player.address || 'غير محدد'}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold">الاشتراك:</h6>
                                <span class="badge bg-success">${player.paid || 0} ريال</span>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold">الحضور:</h6>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: ${player.attendance_rate || 0}%"></div>
                                </div>
                                <small class="text-muted">${player.attendance_rate || 0}%</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-edit btn-action flex-fill" onclick="editPlayer(${player.id})">
                                    <i class="fas fa-edit me-1"></i>تعديل
                                </button>
                                <button class="btn btn-delete btn-action flex-fill" onclick="deletePlayer(${player.id})">
                                    <i class="fas fa-trash me-1"></i>حذف
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        function updateStats(players) {
            const total = players.length;
            const active = players.filter(p => p.status === 'active').length;
            const suspended = players.filter(p => p.status === 'suspended').length;
            const inactive = players.filter(p => p.status === 'inactive').length;
            
            document.getElementById('totalPlayers').textContent = total;
            document.getElementById('activePlayers').textContent = active;
            document.getElementById('suspendedPlayers').textContent = suspended;
            document.getElementById('inactivePlayers').textContent = inactive;
        }
        
        function getStatusText(status) {
            const statusMap = {
                'active': 'نشط',
                'inactive': 'غير نشط',
                'suspended': 'معلق'
            };
            return statusMap[status] || status;
        }
        
        function calculateAge(birthDate) {
            if (!birthDate) return 'غير محدد';
            const today = new Date();
            const birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            return age;
        }
        
        async function savePlayer() {
            const form = document.getElementById('addPlayerForm');
            const formData = new FormData(form);
            
            const playerData = {
                name: formData.get('name'),
                nationality: formData.get('nationality'),
                id_number: formData.get('id_number'),
                phone: formData.get('phone'),
                address: formData.get('address'),
                dob: formData.get('dob'),
                sport: formData.get('sport'),
                notes: formData.get('notes'),
                academy_id: 1,
                paid: 0.00
            };
            
            try {
                const response = await fetch('../api/academy-requests.php?endpoint=players', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(playerData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('تم إضافة اللاعب بنجاح!');
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addPlayerModal')).hide();
                    loadPlayers();
                } else {
                    alert('حدث خطأ: ' + data.error);
                }
            } catch (error) {
                alert('حدث خطأ في الاتصال: ' + error.message);
            }
        }
        
        async function editPlayer(playerId) {
            const player = allPlayers.find(p => p.id == playerId);
            if (player) {
                // Here you would open an edit modal or redirect to edit page
                alert('تعديل بيانات اللاعب: ' + player.name);
            }
        }
        
        async function deletePlayer(playerId) {
            if (confirm('هل أنت متأكد من حذف هذا اللاعب؟')) {
                try {
                    const response = await fetch(`../api/academy-requests.php?endpoint=players&id=${playerId}`, {
                        method: 'DELETE'
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('تم حذف اللاعب بنجاح!');
                        loadPlayers();
                    } else {
                        alert('حدث خطأ: ' + data.error);
                    }
                } catch (error) {
                    alert('حدث خطأ في الاتصال: ' + error.message);
                }
            }
        }
        
        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter').value;
            const sportFilter = document.getElementById('sportFilter').value;
            const searchInput = document.getElementById('searchInput').value;
            
            let filteredPlayers = allPlayers;
            
            if (statusFilter) {
                filteredPlayers = filteredPlayers.filter(p => p.status === statusFilter);
            }
            
            if (sportFilter) {
                filteredPlayers = filteredPlayers.filter(p => p.sport === sportFilter);
            }
            
            if (searchInput) {
                filteredPlayers = filteredPlayers.filter(p => 
                    p.name.toLowerCase().includes(searchInput.toLowerCase()) ||
                    (p.phone && p.phone.includes(searchInput))
                );
            }
            
            displayPlayers(filteredPlayers);
        }
        
        function showError(message) {
            const playersList = document.getElementById('playersList');
            playersList.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">حدث خطأ</h5>
                    <p class="text-muted">${message}</p>
                    <button class="btn btn-primary" onclick="loadPlayers()">إعادة المحاولة</button>
                </div>
            `;
        }
        
        // Auto-apply filters on input change
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('sportFilter').addEventListener('change', applyFilters);
        document.getElementById('searchInput').addEventListener('input', applyFilters);
    </script>
</body>
</html>
