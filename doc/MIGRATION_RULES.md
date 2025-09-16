# Migration Rules and Documentation - Vult Subscription System

## 📋 Migration Overview

This document outlines the migration rules and database schema changes for the Vult Subscription System integration.

## 🗄️ Database Schema Changes

### 1. User Table Enhancements

**Migration File**: `m240915_000000_add_trial_fields_to_user.php`

#### Added Fields:
- `trial_started_at` (INT, NULL) - Timestamp when trial period started
- `trial_expires_at` (INT, NULL) - Timestamp when trial period expires
- `academy_id` (INT, NULL) - Associated academy ID for the user

#### Indexes Added:
- `idx_user_trial_started_at` - Index on trial_started_at
- `idx_user_trial_expires_at` - Index on trial_expires_at  
- `idx_user_academy_id` - Index on academy_id

### 2. Academy Requests Table

**Model File**: `AcademyRequest.php`

#### Table Structure:
```sql
CREATE TABLE academy_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    academy_name VARCHAR(255) NOT NULL,
    manager_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    address TEXT NULL,
    city VARCHAR(255) NULL,
    branches_count INT NOT NULL,
    sports VARCHAR(255) NULL,
    description TEXT NULL,
    status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
    requested_at INT NOT NULL,
    approved_at INT NULL,
    rejected_at INT NULL,
    notes TEXT NULL,
    created_by INT NULL,
    updated_by INT NULL,
    portal_academy_id INT NULL,
    portal_user_id INT NULL,
    user_id INT NULL,
    created_at INT NULL,
    updated_at INT NULL
);
```

## 🔧 Migration Rules

### 1. **Safe Migration Pattern**
- All migrations use `safeUp()` and `safeDown()` methods
- Proper rollback support for all changes
- Transaction-based operations where needed

### 2. **Index Management**
- Create indexes after adding columns
- Drop indexes before dropping columns
- Use descriptive index names with table prefix

### 3. **Data Integrity**
- Foreign key constraints where applicable
- Proper data type validation
- Default values for required fields

### 4. **Naming Conventions**
- Migration files: `m{timestamp}_{description}.php`
- Index names: `idx_{table}_{column}`
- Foreign key names: `fk_{table}_{referenced_table}`

## 📊 Trial System Logic

### 1. **Trial Period Management**
```php
// Check if user is on trial
public function isTrial()
{
    return $this->trial_expires_at && $this->trial_expires_at > time();
}

// Check if trial has expired
public function isTrialExpired()
{
    return $this->trial_expires_at && $this->trial_expires_at <= time();
}

// Get remaining trial days
public function getTrialDaysLeft()
{
    if (!$this->trial_expires_at) {
        return 0;
    }
    
    $daysLeft = ceil(($this->trial_expires_at - time()) / (24 * 60 * 60));
    return max(0, $daysLeft);
}
```

### 2. **Academy Request Status Management**
```php
const STATUS_PENDING = 'pending';
const STATUS_APPROVED = 'approved';
const STATUS_REJECTED = 'rejected';
const STATUS_EXPIRED = 'expired';

// Approve request
public function approve()
{
    $this->status = self::STATUS_APPROVED;
    $this->approved_at = time();
    return $this->save();
}

// Reject request
public function reject()
{
    $this->status = self::STATUS_REJECTED;
    $this->rejected_at = time();
    return $this->save();
}
```

## 🚀 Running Migrations

### 1. **Development Environment**
```bash
# Run all pending migrations
php yii migrate

# Run specific migration
php yii migrate/up m240915_000000_add_trial_fields_to_user

# Rollback last migration
php yii migrate/down
```

### 2. **Production Environment**
```bash
# Run migrations with confirmation
php yii migrate --interactive=0

# Check migration status
php yii migrate/history
```

## 🔍 Validation Rules

### 1. **Email Validation**
- Must be valid email format
- Unique within academy scope
- Required field for academy requests

### 2. **Phone Validation**
- Saudi and Egyptian phone number formats supported
- Pattern: `/^((009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})|(\+2|002|2)(10|11|12|15)([\d]{8})|(0)(10|11|12|15)([\d]{8}))$/`

### 3. **Status Validation**
- Must be one of: pending, approved, rejected, expired
- Status changes are logged with timestamps

## 📈 Performance Considerations

### 1. **Indexing Strategy**
- Index on frequently queried fields
- Composite indexes for complex queries
- Regular index maintenance

### 2. **Query Optimization**
- Use proper WHERE clauses
- Avoid N+1 queries with proper relations
- Cache frequently accessed data

## 🛡️ Security Rules

### 1. **Data Protection**
- Password hashing using Yii2 security
- Input validation and sanitization
- SQL injection prevention

### 2. **Access Control**
- Role-based permissions
- Academy-scoped data access
- API authentication

## 📝 Documentation Standards

### 1. **Code Documentation**
- PHPDoc comments for all methods
- Inline comments for complex logic
- README files for major components

### 2. **Database Documentation**
- Table structure documentation
- Relationship diagrams
- Data flow documentation

## 🔄 Rollback Procedures

### 1. **Migration Rollback**
```bash
# Rollback to specific migration
php yii migrate/to m240915_000000_add_trial_fields_to_user

# Rollback all migrations
php yii migrate/to 0
```

### 2. **Data Backup**
- Always backup before running migrations
- Test migrations in development first
- Have rollback plan ready

## ✅ Testing Checklist

### 1. **Migration Testing**
- [ ] Migration runs successfully
- [ ] Rollback works correctly
- [ ] Data integrity maintained
- [ ] Performance impact acceptable

### 2. **Application Testing**
- [ ] User registration works
- [ ] Trial system functions
- [ ] Academy requests process correctly
- [ ] All validations work

## 📞 Support and Maintenance

### 1. **Monitoring**
- Monitor migration execution times
- Track database performance
- Log any migration errors

### 2. **Maintenance**
- Regular database optimization
- Index maintenance
- Cleanup old data

---

**Last Updated**: 2024-01-15
**Version**: 1.0
**Maintainer**: Vult Development Team

