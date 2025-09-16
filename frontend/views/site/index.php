<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Vult SaaS - Academy Management Platform';
?>

<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 font-weight-bold mb-4">
                    Manage Your Academy Like a Pro
                </h1>
                <p class="lead mb-4">
                    The complete solution for sports academies, training centers, and educational institutions. 
                    Manage students, schedules, payments, and more in one powerful platform.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="<?= Url::to(['signup']) ?>" class="btn btn-light btn-lg px-4 py-3">
                        <i class="fas fa-rocket me-2"></i>Start Free Trial
                    </a>
                    <a href="<?= Url::to(['pricing']) ?>" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="fas fa-tag me-2"></i>View Pricing
                    </a>
                </div>
                <div class="mt-4">
                    <small class="text-light">
                        <i class="fas fa-check-circle me-1"></i> 7-day free trial
                        <i class="fas fa-check-circle ms-3 me-1"></i> No credit card required
                        <i class="fas fa-check-circle ms-3 me-1"></i> Cancel anytime
                    </small>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <img src="/images/dashboard-preview.png" alt="Dashboard Preview" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 font-weight-bold mb-3">Everything You Need to Run Your Academy</h2>
                <p class="lead text-muted">
                    Our comprehensive platform provides all the tools you need to manage your academy efficiently and grow your business.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card h-100 p-4 text-center border rounded shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="font-weight-bold">Student Management</h5>
                    <p class="text-muted">Complete student profiles, attendance tracking, progress monitoring, and parent communication.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card h-100 p-4 text-center border rounded shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-calendar-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="font-weight-bold">Schedule Management</h5>
                    <p class="text-muted">Create and manage class schedules, trainer assignments, and facility bookings with ease.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card h-100 p-4 text-center border rounded shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-credit-card fa-3x text-primary"></i>
                    </div>
                    <h5 class="font-weight-bold">Payment Processing</h5>
                    <p class="text-muted">Accept payments, manage subscriptions, generate invoices, and track financial performance.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card h-100 p-4 text-center border rounded shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-chart-line fa-3x text-primary"></i>
                    </div>
                    <h5 class="font-weight-bold">Analytics & Reports</h5>
                    <p class="text-muted">Detailed insights into your academy's performance with customizable reports and dashboards.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card h-100 p-4 text-center border rounded shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="font-weight-bold">Mobile App</h5>
                    <p class="text-muted">Access your academy management tools on the go with our mobile app for iOS and Android.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card h-100 p-4 text-center border rounded shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5 class="font-weight-bold">24/7 Support</h5>
                    <p class="text-muted">Get help when you need it with our dedicated support team and comprehensive documentation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="pricing-section bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 font-weight-bold mb-3">Simple, Transparent Pricing</h2>
                <p class="lead text-muted">
                    Choose the plan that fits your academy's needs. All plans include our core features.
                </p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="pricing-card h-100 p-4 text-center border rounded shadow-sm">
                            <h5 class="font-weight-bold mb-3">Starter</h5>
                            <div class="price mb-3">
                                <span class="display-4 font-weight-bold">$29</span>
                                <span class="text-muted">/month</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Up to 2 branches</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Up to 100 students</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Basic reporting</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email support</li>
                            </ul>
                            <a href="<?= Url::to(['signup']) ?>" class="btn btn-outline-primary btn-block">Start Free Trial</a>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="pricing-card h-100 p-4 text-center border rounded shadow-sm border-primary position-relative">
                            <div class="badge bg-primary position-absolute" style="top: -10px; left: 50%; transform: translateX(-50%);">
                                Most Popular
                            </div>
                            <h5 class="font-weight-bold mb-3">Professional</h5>
                            <div class="price mb-3">
                                <span class="display-4 font-weight-bold">$79</span>
                                <span class="text-muted">/month</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Up to 5 branches</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Up to 500 students</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Advanced reporting</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Priority support</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Mobile app access</li>
                            </ul>
                            <a href="<?= Url::to(['signup']) ?>" class="btn btn-primary btn-block">Start Free Trial</a>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="pricing-card h-100 p-4 text-center border rounded shadow-sm">
                            <h5 class="font-weight-bold mb-3">Enterprise</h5>
                            <div class="price mb-3">
                                <span class="display-4 font-weight-bold">$199</span>
                                <span class="text-muted">/month</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited branches</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited students</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Custom reporting</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 24/7 support</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> API access</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Custom integrations</li>
                            </ul>
                            <a href="<?= Url::to(['contact']) ?>" class="btn btn-outline-primary btn-block">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 font-weight-bold mb-3">Ready to Transform Your Academy?</h2>
                <p class="lead mb-4">
                    Join thousands of academies already using Vult SaaS to manage their operations more efficiently.
                </p>
                <a href="<?= Url::to(['signup']) ?>" class="btn btn-light btn-lg px-5 py-3">
                    <i class="fas fa-rocket me-2"></i>Start Your Free Trial Today
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 80vh;
    display: flex;
    align-items: center;
}

.feature-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.pricing-card {
    transition: transform 0.3s ease;
}

.pricing-card:hover {
    transform: translateY(-5px);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
</style>
