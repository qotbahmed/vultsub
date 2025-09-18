ش# Vult SaaS Subscription System - Complete Documentation

## Phase 1: Trial Registration & Activation

---

### 1. Overview

A streamlined customer registration process that automatically activates a 7-day free trial, allowing new customers to experience the full Vult academy management system without restrictions.

---

### 2. Scope

**Included:**
- Simple sign-up form with essential data collection
- Automatic account creation and trial activation
- Email verification process
- Welcome email with login credentials
- Full system access during trial period
- Automatic trial expiration notifications

**Excluded:**
- Complex onboarding workflows
- Payment collection during trial
- Advanced customization during trial

---

### 3. Persona

**Primary:** New academy owners looking to manage their sports academies
**Secondary:** Academy administrators who will manage day-to-day operations

---

### 4. User Experience (UX)

1. Customer visits the Vult sign-up page
2. Fills out simple form with: name, email, academy name, number of branches, phone number
3. Creates password directly on the form
4. Clicks "Start Free Trial" button
5. Receives email verification link
6. Clicks verification link to activate account
7. Receives welcome email with login credentials
8. Logs into system with full access to all features
9. Receives notifications about trial period remaining

---

### 5. User Story

**As a new academy owner, I want to quickly register and start a free trial so that I can evaluate the Vult system before committing to a paid subscription.**

---

### 6. Acceptance Criteria

**Given/When/Then Format:**

- **Given** a new user visits the sign-up page
- **When** they fill out the registration form with valid data
- **Then** their account should be created and trial activated immediately

- **Given** a user completes registration
- **When** they verify their email
- **Then** they should receive a welcome email with login credentials

- **Given** a user is in trial period
- **When** they log into the system
- **Then** they should have access to all features without restrictions

- **Given** a trial is about to expire
- **When** 1-2 days remain
- **Then** the user should receive notification emails

---

### 7. Business Logic

- Trial period is exactly 7 days from account creation
- All trial accounts have full system access
- Email verification is mandatory before account activation
- Trial accounts are marked with "Trial Account" indicator
- System automatically tracks trial expiration dates
- Trial data includes: academy settings, working days, schedules, activities, packages, discounts, logo
- Trial accounts are restored to default data weekly
- Only essential data is preserved when transitioning to paid subscription

---

### 8. Technical Architecture

**Backend:**
- Yii2 framework for account management
- Email service for verification and notifications
- Database triggers for trial expiration tracking
- Automated cron jobs for trial management
- RESTful API for account creation

**Frontend:**
- Responsive sign-up form
- Email verification page
- Dashboard with trial status indicator
- Notification system for trial reminders

**Integrations:**
- SMTP service for email delivery
- Push notification service (optional)
- Database backup/restore system for trial data

**Security:**
- Email verification before activation
- Password hashing and encryption
- Rate limiting on sign-up attempts
- CSRF protection on forms

---

### 9. Data Structure (Database Tables)

**Table: users**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Unique user ID        |
| name           | VARCHAR   | NOT NULL                   | Full name             |
| email          | VARCHAR   | UNIQUE, NOT NULL           | Login email           |
| phone          | VARCHAR   | NULL                       | Phone number          |
| password_hash  | VARCHAR   | NOT NULL                   | Encrypted password    |
| email_verified | BOOLEAN   | DEFAULT FALSE              | Email verification    |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Account creation date |
| updated_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Last update           |

**Table: academies**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Academy ID            |
| user_id        | INT (FK)  | NOT NULL                   | Linked to users.id    |
| name           | VARCHAR   | NOT NULL                   | Academy name          |
| branches_count | INT       | NOT NULL                   | Number of branches    |
| logo           | VARCHAR   | NULL                       | Academy logo path     |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Academy creation      |

**Table: subscriptions**

| Field       | Type     | Constraints                | Description           |
| ----------- | -------- | -------------------------- | --------------------- |
| id          | INT (PK) | Auto Increment             | Subscription ID       |
| user_id     | INT (FK) | NOT NULL                   | Linked to users.id    |
| type        | ENUM     | ('trial','paid','expired') | Subscription type     |
| start_date  | DATE     | NOT NULL                   | Subscription start    |
| end_date    | DATE     | NOT NULL                   | Subscription end      |
| status      | ENUM     | ('active','expired','locked') | Subscription state |
| created_at  | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Subscription creation |

**Table: academy_settings**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Settings ID           |
| academy_id     | INT (FK)  | NOT NULL                   | Linked to academies.id|
| working_days   | JSON      | NULL                       | Working days config   |
| schedules      | JSON      | NULL                       | Schedule templates    |
| activities     | JSON      | NULL                       | Available activities  |
| packages       | JSON      | NULL                       | Pricing packages      |
| discounts      | JSON      | NULL                       | Discount rules        |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Settings creation     |

---

### 10. Testing Scenarios

**Functional Tests:**
- User can complete registration form successfully
- Email verification process works correctly
- Trial account has full system access
- Welcome email is sent with correct credentials

**Edge Cases:**
- Invalid email format handling
- Duplicate email registration attempts
- Email verification link expiration
- Trial account data restoration

**Performance Tests:**
- Registration form submission under load
- Email delivery performance
- Database queries for trial management

**Security Tests:**
- Email verification bypass attempts
- SQL injection on registration form
- Rate limiting effectiveness

---

## Phase 2: Paid Subscription Transition

---

### 1. Overview

Seamless transition from trial to paid subscription with clear pricing options, secure payment processing, and automatic account activation upon successful payment.

---

### 2. Scope

**Included:**
- Trial expiration notifications
- Account locking after trial ends
- Subscription plans display
- Payment gateway integration (Paylink)
- Automatic subscription activation
- Trial-to-paid data migration

**Excluded:**
- Manual payment processing
- Complex pricing negotiations
- Custom payment terms

---

### 3. Persona

**Primary:** Trial users ready to upgrade to paid subscription
**Secondary:** Academy owners evaluating different pricing tiers

---

### 4. User Experience (UX)

1. User receives trial expiration notification (1-2 days before)
2. Trial expires and account is locked
3. User attempts to login and is redirected to subscription plans
4. User views available plans with clear pricing and features
5. User selects plan based on branch count and duration
6. User is redirected to Paylink payment gateway
7. User completes payment securely
8. Payment is processed and subscription is activated
9. User receives confirmation email
10. User can access system with paid subscription features

---

### 5. User Story

**As a trial user, I want to easily upgrade to a paid subscription so that I can continue using the Vult system after my trial expires.**

---

### 6. Acceptance Criteria

**Given/When/Then Format:**

- **Given** a trial is about to expire
- **When** 1-2 days remain
- **Then** the user should receive notification emails

- **Given** a trial has expired
- **When** the user tries to login
- **Then** they should be redirected to subscription plans

- **Given** a user selects a subscription plan
- **When** they complete payment
- **Then** their subscription should be activated immediately

- **Given** a user has an active paid subscription
- **When** they login
- **Then** the trial indicator should be removed

---

### 7. Business Logic

- Trial accounts are locked exactly 7 days after creation
- Subscription plans are based on branch count + duration (monthly, semi-annual, annual)
- Discounts apply to semi-annual and annual plans
- Only essential data is preserved during trial-to-paid transition
- Payment must be completed before account unlock
- Failed payments result in continued account lock
- Successful payments trigger immediate subscription activation

---

### 8. Technical Architecture

**Backend:**
- Yii2 subscription management
- Paylink API integration
- Webhook handling for payment confirmations
- Database triggers for account locking/unlocking
- Cron jobs for trial expiration management


**Integrations:**
- Paylink payment gateway
- Email service for notifications
- Webhook endpoints for payment callbacks

**Security:**
- Secure payment processing
- Webhook signature verification
- Account locking mechanisms
- Payment data encryption

---

### 9. Data Structure (Database Tables)

**Table: subscription_plans**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Plan ID               |
| name           | VARCHAR   | NOT NULL                   | Plan name             |
| branch_limit   | INT       | NOT NULL                   | Maximum branches      |
| duration       | ENUM      | ('monthly','semi_annual','annual') | Plan duration |
| price          | DECIMAL   | NOT NULL                   | Plan price            |
| discount       | DECIMAL   | DEFAULT 0                  | Discount percentage   |
| features       | JSON      | NULL                       | Plan features         |
| is_active      | BOOLEAN   | DEFAULT TRUE               | Plan availability     |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Plan creation         |

**Table: payments**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Payment ID            |
| user_id        | INT (FK)  | NOT NULL                   | Linked to users.id    |
| subscription_id| INT (FK)  | NOT NULL                   | Linked to subscriptions.id |
| plan_id        | INT (FK)  | NOT NULL                   | Linked to subscription_plans.id |
| amount         | DECIMAL   | NOT NULL                   | Payment amount        |
| currency       | VARCHAR   | DEFAULT 'SAR'              | Payment currency      |
| payment_method | VARCHAR   | NOT NULL                   | Payment method        |
| transaction_id | VARCHAR   | UNIQUE                     | Gateway transaction ID|
| status         | ENUM      | ('pending','completed','failed','refunded') | Payment status |
| gateway_response| JSON     | NULL                       | Gateway response data |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Payment creation      |
| updated_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Payment update        |

**Table: subscription_history**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | History ID            |
| user_id        | INT (FK)  | NOT NULL                   | Linked to users.id    |
| subscription_id| INT (FK)  | NOT NULL                   | Linked to subscriptions.id |
| action         | ENUM      | ('created','activated','expired','renewed','cancelled') | Action type |
| old_status     | VARCHAR   | NULL                       | Previous status       |
| new_status     | VARCHAR   | NOT NULL                   | New status            |
| notes          | TEXT      | NULL                       | Action notes          |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Action timestamp      |

---

### 10. Testing Scenarios

**Functional Tests:**
- Trial expiration notifications are sent correctly
- Account locking works after trial expires
- Subscription plans display correctly
- Payment processing completes successfully
- Account unlocking after successful payment

**Edge Cases:**
- Payment gateway failures
- Duplicate payment attempts
- Subscription plan changes during payment
- Network timeouts during payment

**Performance Tests:**
- Payment gateway response times
- Database queries during subscription activation
- Concurrent payment processing

**Security Tests:**
- Payment data encryption
- Webhook signature verification
- Account locking bypass attempts

---

## Phase 3: Subscription Management & Support

---

### 1. Overview

Comprehensive subscription management system providing users with billing information, automatic renewals, invoice management, and technical support channels.

---

### 2. Scope

**Included:**
- User dashboard with subscription status
- Automatic renewal management
- Invoice and payment history
- Technical support ticketing system
- Subscription renewal notifications
- Account management features

**Excluded:**
- Manual invoice generation
- Custom billing arrangements
- Advanced financial reporting

---

### 3. Persona

**Primary:** Active subscribers managing their accounts
**Secondary:** Users requiring technical support

---

### 4. User Experience (UX)

1. User logs into their account dashboard
2. Views current subscription status and renewal date
3. Accesses billing history and downloads invoices
4. Receives renewal notifications before auto-renewal
5. Can contact support through ticketing system
6. Receives support responses via email and dashboard
7. Can manage account settings and preferences
8. Views upcoming charges and renewal dates

---

### 5. User Story

**As a subscribed user, I want to manage my subscription and access support so that I can maintain my account and get help when needed.**

---

### 6. Acceptance Criteria

**Given/When/Then Format:**

- **Given** a user has an active subscription
- **When** they access their dashboard
- **Then** they should see their subscription status and renewal date

- **Given** a subscription is about to renew
- **When** 1 week before renewal
- **Then** the user should receive notification email

- **Given** a user needs support
- **When** they submit a support ticket
- **Then** they should receive confirmation and response tracking

- **Given** a user wants to view billing history
- **When** they access the billing section
- **Then** they should see all invoices and payments

---

### 7. Business Logic

- Automatic renewals for monthly and annual subscriptions
- Renewal notifications sent 1 week before charge
- Support tickets are available during trial and paid periods
- Invoice generation for all successful payments
- Subscription status updates after successful renewals
- Failed renewals trigger account suspension after grace period

---

### 8. Technical Architecture

**Backend:**
- Yii2 subscription management system
- Automated renewal processing
- Invoice generation system
- Ticketing system with email integration
- Payment gateway webhook handling

**Frontend:**
- User dashboard with subscription overview
- Billing history interface
- Support ticket creation and tracking
- Account settings management

**Integrations:**
- Paylink for automatic renewals
- Email service for notifications
- PDF generation for invoices
- Support ticketing system

**Security:**
- Secure payment processing
- User authentication for dashboard access
- Support ticket privacy and security
- Invoice data protection

---

### 9. Data Structure (Database Tables)

**Table: invoices**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Invoice ID            |
| user_id        | INT (FK)  | NOT NULL                   | Linked to users.id    |
| payment_id     | INT (FK)  | NOT NULL                   | Linked to payments.id |
| invoice_number | VARCHAR   | UNIQUE, NOT NULL           | Invoice number        |
| amount         | DECIMAL   | NOT NULL                   | Invoice amount        |
| tax_amount     | DECIMAL   | DEFAULT 0                  | Tax amount            |
| total_amount   | DECIMAL   | NOT NULL                   | Total amount          |
| status         | ENUM      | ('draft','sent','paid','overdue') | Invoice status |
| due_date       | DATE      | NOT NULL                   | Payment due date      |
| paid_date      | DATE      | NULL                       | Payment date          |
| pdf_path       | VARCHAR   | NULL                       | Invoice PDF path      |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Invoice creation      |

**Table: support_tickets**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Ticket ID             |
| user_id        | INT (FK)  | NOT NULL                   | Linked to users.id    |
| subject        | VARCHAR   | NOT NULL                   | Ticket subject        |
| description    | TEXT      | NOT NULL                   | Ticket description    |
| priority       | ENUM      | ('low','medium','high','urgent') | Ticket priority |
| status         | ENUM      | ('open','in_progress','resolved','closed') | Ticket status |
| assigned_to    | INT (FK)  | NULL                       | Assigned support agent |
| resolution     | TEXT      | NULL                       | Resolution notes      |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Ticket creation       |
| updated_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Last update           |
| resolved_at    | TIMESTAMP | NULL                       | Resolution timestamp  |

**Table: support_messages**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Message ID            |
| ticket_id      | INT (FK)  | NOT NULL                   | Linked to support_tickets.id |
| sender_id      | INT (FK)  | NOT NULL                   | Message sender        |
| sender_type    | ENUM      | ('user','support')         | Sender type           |
| message        | TEXT      | NOT NULL                   | Message content       |
| attachments    | JSON      | NULL                       | File attachments      |
| is_read        | BOOLEAN   | DEFAULT FALSE              | Read status           |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Message creation      |

**Table: renewal_notifications**

| Field          | Type      | Constraints                | Description           |
| -------------- | --------- | -------------------------- | --------------------- |
| id             | INT (PK)  | Auto Increment             | Notification ID       |
| user_id        | INT (FK)  | NOT NULL                   | Linked to users.id    |
| subscription_id| INT (FK)  | NOT NULL                   | Linked to subscriptions.id |
| notification_type| ENUM    | ('renewal_reminder','payment_failed','renewal_success') | Notification type |
| days_before    | INT       | NULL                       | Days before renewal   |
| sent_at        | TIMESTAMP | NULL                       | Sent timestamp        |
| status         | ENUM      | ('pending','sent','failed') | Notification status |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Notification creation |

---

### 10. Testing Scenarios

**Functional Tests:**
- Dashboard displays correct subscription information
- Automatic renewals process successfully
- Invoice generation works correctly
- Support ticket creation and tracking
- Renewal notifications are sent on time

**Edge Cases:**
- Failed automatic renewals
- Support ticket escalation
- Invoice generation errors
- Notification delivery failures

**Performance Tests:**
- Dashboard loading with large billing history
- Concurrent support ticket processing
- Invoice generation performance
- Email notification delivery

**Security Tests:**
- User data privacy in support tickets
- Invoice access permissions
- Payment information security
- Support agent access controls

---

## System Integration Notes

### Data Migration Strategy

**Trial to Paid Transition:**
- Preserve: Academy settings, working days, schedules, activities, packages, discounts, trainers, appointments
- Remove: Trial-specific data, temporary configurations, test data
- Restore: Default database state for new trial accounts weekly

### Weekly Trial Data Restoration

- Automated cron job runs weekly
- Resets trial accounts to default state
- Preserves only essential academy configuration
- Ensures consistent trial experience for all users

### Support Availability

- Support channels available during trial and paid periods
- Ticketing system accessible from user dashboard
- Email notifications for support responses
- Priority support for paid subscribers

---

👉 This comprehensive documentation covers all three phases of the Vult SaaS subscription system with detailed technical specifications, database structures, and testing scenarios for consistent implementation.
