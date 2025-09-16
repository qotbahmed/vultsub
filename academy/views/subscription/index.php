<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Alert;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $subscription common\models\Subscription */
/* @var $plans common\models\SubscriptionPlan[] */

$this->title = 'Subscription Management';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="subscription-index">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-credit-card me-2"></i>Subscription Management
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <?= Alert::widget([
                                'body' => Yii::$app->session->getFlash('success'),
                                'options' => ['class' => 'alert-success']
                            ]) ?>
                        <?php endif; ?>

                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <?= Alert::widget([
                                'body' => Yii::$app->session->getFlash('error'),
                                'options' => ['class' => 'alert-danger']
                            ]) ?>
                        <?php endif; ?>

                        <!-- Current Subscription Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="subscription-status-card p-4 border rounded">
                                    <h5 class="mb-3">Current Status</h5>
                                    
                                    <?php if ($user->isTrialActive()): ?>
                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-clock me-2"></i>Trial Period</h6>
                                            <p class="mb-2">You are currently on a free trial that expires on <strong><?= date('F j, Y', strtotime($user->trial_ends_at)) ?></strong></p>
                                            <p class="mb-0">Upgrade to a paid plan to continue using all features after your trial ends.</p>
                                        </div>
                                    <?php elseif ($user->isTrialExpired()): ?>
                                        <div class="alert alert-warning">
                                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Trial Expired</h6>
                                            <p class="mb-0">Your trial has expired. Please choose a subscription plan to continue using the platform.</p>
                                        </div>
                                    <?php elseif ($subscription && $subscription->isActive()): ?>
                                        <div class="alert alert-success">
                                            <h6><i class="fas fa-check-circle me-2"></i>Active Subscription</h6>
                                            <p class="mb-2">You have an active subscription to the <strong><?= $subscription->plan->name ?></strong> plan.</p>
                                            <p class="mb-0">Next billing date: <strong><?= date('F j, Y', strtotime($subscription->current_period_end)) ?></strong></p>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-danger">
                                            <h6><i class="fas fa-times-circle me-2"></i>No Active Subscription</h6>
                                            <p class="mb-0">Please choose a subscription plan to continue using the platform.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Plans -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-4">Choose Your Plan</h5>
                            </div>
                        </div>

                        <div class="row">
                            <?php foreach ($plans as $plan): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="plan-card h-100 p-4 border rounded shadow-sm <?= $subscription && $subscription->plan_id == $plan->id ? 'border-primary' : '' ?>">
                                        <?php if ($subscription && $subscription->plan_id == $plan->id): ?>
                                            <div class="badge bg-primary mb-3">Current Plan</div>
                                        <?php endif; ?>
                                        
                                        <h5 class="plan-name mb-3"><?= Html::encode($plan->name) ?></h5>
                                        
                                        <div class="plan-description mb-4">
                                            <p class="text-muted"><?= Html::encode($plan->description) ?></p>
                                        </div>

                                        <div class="plan-pricing mb-4">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="price-option text-center">
                                                        <div class="price-amount">$<?= number_format($plan->price_monthly, 0) ?></div>
                                                        <div class="price-period">per month</div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="price-option text-center">
                                                        <div class="price-amount">$<?= number_format($plan->price_yearly, 0) ?></div>
                                                        <div class="price-period">per year</div>
                                                        <div class="price-discount">Save <?= $plan->getYearlyDiscount() ?>%</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="plan-features mb-4">
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Up to <?= $plan->branches_limit ?> branches
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Up to <?= number_format($plan->students_limit) ?> students
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    <?= number_format($plan->storage_limit_mb) ?> MB storage
                                                </li>
                                                <?php 
                                                $features = $plan->getFeaturesArray();
                                                foreach ($features as $feature): 
                                                ?>
                                                    <li class="mb-2">
                                                        <i class="fas fa-check text-success me-2"></i>
                                                        <?= Html::encode($feature) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>

                                        <div class="plan-actions">
                                            <?php if ($subscription && $subscription->plan_id == $plan->id): ?>
                                                <button class="btn btn-outline-primary btn-block" disabled>
                                                    Current Plan
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-primary btn-block subscribe-btn" 
                                                        data-plan-id="<?= $plan->id ?>"
                                                        data-plan-name="<?= Html::encode($plan->name) ?>">
                                                    <?= $user->isTrialActive() || $user->isTrialExpired() ? 'Subscribe Now' : 'Upgrade Plan' ?>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Current Subscription Actions -->
                        <?php if ($subscription && $subscription->isActive()): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Subscription Management</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Current Plan Details</h6>
                                                    <p><strong>Plan:</strong> <?= $subscription->plan->name ?></p>
                                                    <p><strong>Amount:</strong> $<?= number_format($subscription->amount, 2) ?> <?= strtoupper($subscription->currency) ?> per <?= $subscription->interval ?></p>
                                                    <p><strong>Status:</strong> 
                                                        <span class="badge bg-<?= $subscription->isActive() ? 'success' : 'danger' ?>">
                                                            <?= ucfirst($subscription->status) ?>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Actions</h6>
                                                    <div class="btn-group-vertical w-100">
                                                        <button class="btn btn-outline-primary mb-2" id="update-plan-btn">
                                                            <i class="fas fa-edit me-2"></i>Change Plan
                                                        </button>
                                                        <button class="btn btn-outline-warning mb-2" id="update-payment-btn">
                                                            <i class="fas fa-credit-card me-2"></i>Update Payment Method
                                                        </button>
                                                        <button class="btn btn-outline-danger" id="cancel-subscription-btn">
                                                            <i class="fas fa-times me-2"></i>Cancel Subscription
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subscription Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscribe to <span id="modal-plan-name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="subscription-form">
                    <input type="hidden" id="selected-plan-id" name="plan_id">
                    <input type="hidden" id="selected-interval" name="interval" value="monthly">
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Billing Interval</h6>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="billing-interval" id="monthly" value="monthly" checked>
                                <label class="btn btn-outline-primary" for="monthly">Monthly</label>
                                
                                <input type="radio" class="btn-check" name="billing-interval" id="yearly" value="yearly">
                                <label class="btn btn-outline-primary" for="yearly">Yearly (Save 20%)</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Payment Method</h6>
                            <div id="card-element">
                                <!-- Stripe Elements will be inserted here -->
                            </div>
                            <div id="card-errors" class="text-danger mt-2"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agree-terms" required>
                                <label class="form-check-label" for="agree-terms">
                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-subscription">
                    <i class="fas fa-credit-card me-2"></i>Subscribe Now
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.plan-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.plan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.price-amount {
    font-size: 2rem;
    font-weight: bold;
    color: #007bff;
}

.price-period {
    font-size: 0.9rem;
    color: #6c757d;
}

.price-discount {
    font-size: 0.8rem;
    color: #28a745;
    font-weight: bold;
}

.subscription-status-card {
    background: #f8f9fa;
}

.btn-group-vertical .btn {
    text-align: left;
}
</style>

<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Stripe
    const stripe = Stripe('<?= Yii::$app->params['stripe']['publishableKey'] ?>');
    const elements = stripe.elements();
    
    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
        },
    });
    
    cardElement.mount('#card-element');
    
    // Handle form errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Handle subscription button clicks
    document.querySelectorAll('.subscribe-btn').forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            const planName = this.getAttribute('data-plan-name');
            
            document.getElementById('selected-plan-id').value = planId;
            document.getElementById('modal-plan-name').textContent = planName;
            
            $('#subscriptionModal').modal('show');
        });
    });
    
    // Handle billing interval change
    document.querySelectorAll('input[name="billing-interval"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('selected-interval').value = this.value;
        });
    });
    
    // Handle subscription confirmation
    document.getElementById('confirm-subscription').addEventListener('click', function() {
        const form = document.getElementById('subscription-form');
        const formData = new FormData(form);
        
        // Create payment method
        stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        }).then(function(result) {
            if (result.error) {
                document.getElementById('card-errors').textContent = result.error.message;
            } else {
                // Submit subscription
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= Url::to(['subscribe']) ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                const params = new URLSearchParams();
                params.append('plan_id', formData.get('plan_id'));
                params.append('interval', formData.get('interval'));
                params.append('payment_method_id', result.paymentMethod.id);
                
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            location.reload();
                        } else {
                            alert('Error: ' + xhr.responseText);
                        }
                    }
                };
                
                xhr.send(params.toString());
            }
        });
    });
});
</script>
