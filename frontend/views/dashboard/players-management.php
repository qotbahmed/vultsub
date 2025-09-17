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
                    <h3 class="mb-1"><?= $stats['total_players'] ?></h3>
                    <p class="mb-0">إجمالي اللاعبين</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1"><?= $stats['active_players'] ?></h3>
                    <p class="mb-0">لاعب نشط</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1"><?= $stats['new_players'] ?></h3>
                    <p class="mb-0">لاعب جديد</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1"><?= number_format($stats['average_attendance'], 1) ?>%</h3>
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
                <?php if (empty($dataProvider->getModels())): ?>
                    <div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا يوجد لاعبين بعد</h5>
                            <p class="text-muted">ابدأ بإضافة لاعبين جدد إلى الأكاديمية</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                                <i class="fas fa-plus me-2"></i>إضافة أول لاعب
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($dataProvider->getModels() as $player): ?>
                        <?php
                        $age = $player->date_of_birth ? floor((time() - strtotime($player->date_of_birth)) / 31556926) : 'غير محدد';
                        $statusClass = 'status-inactive';
                        $statusText = 'غير نشط';
                        
                        switch($player->status) {
                            case 'active':
                                $statusClass = 'status-active';
                                $statusText = 'نشط';
                                break;
                            case 'inactive':
                                $statusClass = 'status-inactive';
                                $statusText = 'غير نشط';
                                break;
                            case 'suspended':
                                $statusClass = 'status-pending';
                                $statusText = 'معلق';
                                break;
                        }
                        ?>
                        <div class="col-md-6 col-lg-4 player-item" data-status="<?= $player->status ?>">
                            <div class="player-card">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <div class="player-avatar">
                                            <?= strtoupper(substr($player->name, 0, 1)) ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="mb-1 fw-bold"><?= Html::encode($player->name) ?></h6>
                                        <p class="mb-1 text-muted small"><?= Html::encode($player->sport) ?></p>
                                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                    </div>
                                    <div class="col-3 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editPlayer(<?= $player->id ?>)"><i class="fas fa-edit me-2"></i>تعديل</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="viewPlayer(<?= $player->id ?>)"><i class="fas fa-eye me-2"></i>عرض التفاصيل</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="<?= Url::to(['delete-player', 'id' => $player->id]) ?>" onclick="return confirm('هل أنت متأكد من حذف هذا اللاعب؟')"><i class="fas fa-trash me-2"></i>حذف</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <small class="text-muted">العمر: <?= $age ?> سنة</small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted">المستوى: <?= Html::encode($player->level) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                <div class="modal-body" id="playerModalBody">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Player Modal -->
    <div class="modal fade" id="editPlayerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        تعديل بيانات اللاعب
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editPlayerModalBody">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- View Player Modal -->
    <div class="modal fade" id="viewPlayerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>
                        تفاصيل اللاعب
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewPlayerModalBody">
                    <!-- Content will be loaded via AJAX -->
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

        // Load add player form
        document.getElementById('addPlayerModal').addEventListener('show.bs.modal', function() {
            loadPlayerForm('<?= Url::to(['create-player']) ?>', 'playerModalBody');
        });

        // Load edit player form
        function editPlayer(playerId) {
            loadPlayerForm('<?= Url::to(['update-player']) ?>?id=' + playerId, 'editPlayerModalBody');
            var editModal = new bootstrap.Modal(document.getElementById('editPlayerModal'));
            editModal.show();
        }

        // Load view player details
        function viewPlayer(playerId) {
            console.log('Loading player view for ID:', playerId);
            loadPlayerView('<?= Url::to(['view-player']) ?>?id=' + playerId, 'viewPlayerModalBody');
            var viewModal = new bootstrap.Modal(document.getElementById('viewPlayerModal'));
            viewModal.show();
        }

        // Load player form via AJAX
        function loadPlayerForm(url, containerId) {
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById(containerId).innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading form:', error);
                    document.getElementById(containerId).innerHTML = '<div class="alert alert-danger">حدث خطأ في تحميل النموذج</div>';
                });
        }

        // Load player view via AJAX
        function loadPlayerView(url, containerId) {
            console.log('Loading player view from URL:', url);
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Player view loaded successfully');
                    document.getElementById(containerId).innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading player view:', error);
                    document.getElementById(containerId).innerHTML = '<div class="alert alert-danger"><h5><i class="fas fa-exclamation-triangle me-2"></i>خطأ في تحميل التفاصيل</h5><p>' + error.message + '</p><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button></div>';
                });
        }

        // Handle form submission
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'player-form') {
                e.preventDefault();
                
                const formData = new FormData(e.target);
                const url = e.target.action || '<?= Url::to(['create-player']) ?>';
                
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        // Handle validation errors
                        console.error('Validation errors:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    location.reload(); // Fallback to page reload
                });
            }
        });
    </script>
</body>
</html>
