<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انضم إلى Vult - منصة إدارة الأكاديميات الرياضية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff6b35 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .signup-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        
        .btn-sports {
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
        
        .btn-sports::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-sports:hover::before {
            left: 100%;
        }
        
        .btn-sports:hover {
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
        
        .form-check-label {
            color: #2c3e50 !important;
            font-weight: 500;
            font-size: 16px;
            margin-right: 10px;
        }
        
        .form-check-input:checked + .form-check-label {
            color: #ff6b35 !important;
            font-weight: 600;
        }
        
        .form-check-input:checked {
            background-color: #ff6b35;
            border-color: #ff6b35;
        }
        
        .sports-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sports-title {
            color: #2c3e50 !important;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="signup-card p-5">
                        <div class="text-center mb-4">
                            <div class="sports-icon">
                                <i class="fas fa-futbol text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-3">انضم إلى Vult اليوم!</h2>
                            <p class="text-muted fs-5">ابدأ تجربتك المجانية لمدة 7 أيام</p>
                        </div>
                        
                        <form id="signupForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="academyName" class="form-label fw-bold text-dark">اسم الأكاديمية *</label>
                                    <input type="text" class="form-control" id="academyName" name="academy_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="managerName" class="form-label fw-bold text-dark">اسم المسؤول *</label>
                                    <input type="text" class="form-control" id="managerName" name="manager_name" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold text-dark">البريد الإلكتروني *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-bold text-dark">كلمة المرور *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold text-dark">رقم الهاتف</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label fw-bold text-dark">المدينة</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="branchesCount" class="form-label fw-bold text-dark">عدد الفروع</label>
                                    <select class="form-select" id="branchesCount" name="branches_count">
                                        <option value="1">فرع واحد</option>
                                        <option value="2">فرعين</option>
                                        <option value="3">3 فروع</option>
                                        <option value="4">4 فروع</option>
                                        <option value="5">5 فروع</option>
                                        <option value="6">أكثر من 5 فروع</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label fw-bold text-dark">العنوان</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                            </div>
                            
                            <div class="sports-section">
                                <label class="sports-title">الرياضات المقدمة *</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="كرة القدم" name="sports[]" id="football">
                                            <label class="form-check-label" for="football">كرة القدم</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="كرة السلة" name="sports[]" id="basketball">
                                            <label class="form-check-label" for="basketball">كرة السلة</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="كرة الطائرة" name="sports[]" id="volleyball">
                                            <label class="form-check-label" for="volleyball">كرة الطائرة</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="التنس" name="sports[]" id="tennis">
                                            <label class="form-check-label" for="tennis">التنس</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="السباحة" name="sports[]" id="swimming">
                                            <label class="form-check-label" for="swimming">السباحة</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="الجمباز" name="sports[]" id="gymnastics">
                                            <label class="form-check-label" for="gymnastics">الجمباز</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="ألعاب القوى" name="sports[]" id="athletics">
                                            <label class="form-check-label" for="athletics">ألعاب القوى</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="الكاراتيه" name="sports[]" id="karate">
                                            <label class="form-check-label" for="karate">الكاراتيه</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold text-dark">وصف الأكاديمية</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="اكتب وصفاً مختصراً عن أكاديميتك..."></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-sports text-white">
                                    <span class="btn-text">ابدأ تجربتك المجانية</span>
                                    <div class="loading">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        جاري التسجيل...
                                    </div>
                                </button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <p class="text-muted">لديك حساب بالفعل؟ <a href="http://vult-saas.localhost/?subdomain=app" class="text-decoration-none fw-bold" style="color: #ff6b35;">سجل الدخول</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('signupForm').addEventListener('submit', function(event) {
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
            
            // Convert sports array
            data.sports = Array.from(document.querySelectorAll('input[name="sports[]"]:checked')).map(cb => cb.value);
            
            fetch('http://vult-saas.localhost/api/trial-management.php?endpoint=start-trial', {
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
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم التسجيل بنجاح! ستبدأ تجربتك المجانية الآن.';
                    this.appendChild(alertDiv);
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = `http://vult-saas.localhost/trial-dashboard/?email=${data.email}`;
                    }, 2000);
                } else {
                    throw new Error(result.message || 'حدث خطأ أثناء التسجيل');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger mt-3';
                alertDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>خطأ في التسجيل: ' + error.message;
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
