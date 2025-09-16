<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - Vult</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .login-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff6b35 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4);
        }
        
        .sports-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .loading {
            display: none;
        }
        
        .loading.show {
            display: block;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            font-weight: 500;
        }
        
        .platform-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-section">
        <div class="platform-badge">
            <i class="fas fa-globe me-2"></i>
            منصة موحدة
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="login-card p-5">
                        <div class="text-center mb-4">
                            <div class="sports-icon">
                                <i class="fas fa-futbol text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-3">مرحباً بك في Vult</h2>
                            <p class="text-muted fs-5">سجل الدخول للوصول إلى منصتك</p>
                        </div>
                        
                        <form id="loginForm">
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold text-dark">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold text-dark">كلمة المرور</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-login text-white">
                                    <span class="btn-text">تسجيل الدخول</span>
                                    <div class="loading">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        جاري تسجيل الدخول...
                                    </div>
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p class="text-muted">ليس لديك حساب؟ 
                                    <a href="http://vult-saas.localhost/?subdomain=signup" class="text-decoration-none fw-bold" style="color: #ff6b35;">سجل الآن</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const btnText = submitBtn.querySelector('.btn-text');
            const loading = submitBtn.querySelector('.loading');
            
            // Show loading
            btnText.style.display = 'none';
            loading.classList.add('show');
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            fetch('http://vult-saas.localhost/unified-login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success mt-3';
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم تسجيل الدخول بنجاح! جاري التوجيه...';
                    this.appendChild(alertDiv);
                    
                    // Redirect after 1 second
                    setTimeout(() => {
                        window.location.href = result.data.redirect_url;
                    }, 1000);
                } else {
                    throw new Error(result.error || 'حدث خطأ أثناء تسجيل الدخول');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger mt-3';
                alertDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>خطأ في تسجيل الدخول: ' + error.message;
                this.appendChild(alertDiv);
                
                // Reset button
                btnText.style.display = 'inline';
                loading.classList.remove('show');
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>
