<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'إدارة اللاعبين - Vult';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
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
        
        .main-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .player-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .player-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #ff6b35;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
        }
        
        .btn-warning {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .search-box {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        
        .filter-badge {
            background: #e9ecef;
            color: #495057;
            padding: 8px 16px;
            border-radius: 20px;
            margin: 5px;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-badge.active {
            background: #ff6b35;
            color: white;
        }
        
        .player-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">
                        <i class="fas fa-users me-3"></i>
                        إدارة اللاعبين
                    </h1>
                    <p class="mb-0 mt-2">إدارة وتتبع جميع اللاعبين في الأكاديمية</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="<?= Url::to(['dashboard/trial-dashboard']) ?>" class="btn btn-light me-2">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للوحة التحكم
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                        <i class="fas fa-plus me-2"></i>
                        إضافة لاعب جديد
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">150</h3>
                    <p class="mb-0">إجمالي اللاعبين</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">120</h3>
                    <p class="mb-0">لاعب نشط</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">25</h3>
                    <p class="mb-0">لاعب جديد</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">85%</h3>
                    <p class="mb-0">معدل الحضور</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="main-card">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control search-box" placeholder="البحث عن لاعب...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-end">
                        <span class="filter-badge active" data-filter="all">الكل</span>
                        <span class="filter-badge" data-filter="active">نشط</span>
                        <span class="filter-badge" data-filter="inactive">غير نشط</span>
                        <span class="filter-badge" data-filter="new">جديد</span>
                    </div>
                </div>
            </div>

            <!-- Players List -->
            <div class="row" id="playersList">
                <!-- Player Card 1 -->
                <div class="col-md-6 col-lg-4 player-item" data-status="active">
                    <div class="player-card">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <div class="player-avatar">
                                    أ
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 fw-bold">أحمد محمد</h6>
                                <p class="mb-1 text-muted small">كرة القدم</p>
                                <span class="status-badge status-active">نشط</span>
                            </div>
                            <div class="col-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>تعديل</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>عرض التفاصيل</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>حذف</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">العمر: 18 سنة</small>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">معدل الحضور: 90%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Player Card 2 -->
                <div class="col-md-6 col-lg-4 player-item" data-status="active">
                    <div class="player-card">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <div class="player-avatar">
                                    س
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 fw-bold">سارة أحمد</h6>
                                <p class="mb-1 text-muted small">كرة السلة</p>
                                <span class="status-badge status-active">نشط</span>
                            </div>
                            <div class="col-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>تعديل</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>عرض التفاصيل</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>حذف</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">العمر: 16 سنة</small>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">معدل الحضور: 85%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Player Card 3 -->
                <div class="col-md-6 col-lg-4 player-item" data-status="new">
                    <div class="player-card">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <div class="player-avatar">
                                    م
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 fw-bold">محمد علي</h6>
                                <p class="mb-1 text-muted small">التنس</p>
                                <span class="status-badge status-pending">جديد</span>
                            </div>
                            <div class="col-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>تعديل</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>عرض التفاصيل</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>حذف</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">العمر: 14 سنة</small>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">معدل الحضور: 75%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Player Card 4 -->
                <div class="col-md-6 col-lg-4 player-item" data-status="inactive">
                    <div class="player-card">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <div class="player-avatar">
                                    ف
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 fw-bold">فاطمة حسن</h6>
                                <p class="mb-1 text-muted small">السباحة</p>
                                <span class="status-badge status-inactive">غير نشط</span>
                            </div>
                            <div class="col-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>تعديل</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>عرض التفاصيل</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>حذف</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">العمر: 17 سنة</small>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">معدل الحضور: 60%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Player Modal -->
    <div class="modal fade" id="addPlayerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>
                        إضافة لاعب جديد
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الاسم الأول</label>
                                <input type="text" class="form-control" placeholder="أدخل الاسم الأول">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الاسم الأخير</label>
                                <input type="text" class="form-control" placeholder="أدخل الاسم الأخير">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاريخ الميلاد</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="tel" class="form-control" placeholder="أدخل رقم الهاتف">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الرياضة</label>
                                <select class="form-select">
                                    <option>اختر الرياضة</option>
                                    <option>كرة القدم</option>
                                    <option>كرة السلة</option>
                                    <option>التنس</option>
                                    <option>السباحة</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الجنسية</label>
                                <input type="text" class="form-control" placeholder="أدخل الجنسية">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea class="form-control" rows="3" placeholder="أدخل العنوان"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary">إضافة اللاعب</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functionality
        document.querySelectorAll('.filter-badge').forEach(badge => {
            badge.addEventListener('click', function() {
                // Remove active class from all badges
                document.querySelectorAll('.filter-badge').forEach(b => b.classList.remove('active'));
                // Add active class to clicked badge
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                const players = document.querySelectorAll('.player-item');
                
                players.forEach(player => {
                    if (filter === 'all' || player.getAttribute('data-status') === filter) {
                        player.style.display = 'block';
                    } else {
                        player.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        document.querySelector('.search-box').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const players = document.querySelectorAll('.player-item');
            
            players.forEach(player => {
                const playerName = player.querySelector('h6').textContent.toLowerCase();
                if (playerName.includes(searchTerm)) {
                    player.style.display = 'block';
                } else {
                    player.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
