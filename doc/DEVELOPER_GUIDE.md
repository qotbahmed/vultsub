# Developer Guide - Vult Subscription System

## Overview
This guide provides comprehensive information for developers working on the Vult Subscription System, including coding standards, architecture, and development workflows.

## Project Structure

```
vult-sub/
├── api/                    # API application
│   ├── controllers/        # API controllers
│   ├── models/            # API-specific models
│   └── web/               # API entry point
├── backend/               # Backend admin application
│   ├── controllers/       # Admin controllers
│   ├── models/           # Admin models
│   └── views/            # Admin views
├── common/               # Shared components
│   ├── models/           # Shared models
│   ├── migrations/       # Database migrations
│   └── helpers/          # Helper classes
├── console/              # Console commands
│   └── controllers/      # Console controllers
├── frontend/             # Frontend application
│   ├── controllers/      # Frontend controllers
│   ├── models/          # Frontend models
│   └── views/           # Frontend views
└── doc/                 # Documentation
```

## Coding Standards

### PHP Standards
- Follow PSR-12 coding standard
- Use meaningful variable and method names
- Add PHPDoc comments for all public methods
- Maximum line length: 120 characters
- Use type hints where possible

### Example Code Structure
```php
<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Academy Request Model
 * 
 * @property int $id
 * @property string $academy_name
 * @property string $manager_name
 * @property string $email
 * @property string $phone
 * @property string $status
 * @property int $requested_at
 * @property int $approved_at
 * @property int $rejected_at
 */
class AcademyRequest extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academy_requests';
    }

    /**
     * Check if request is pending
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
```

### JavaScript Standards
- Use ES6+ features
- Follow Airbnb JavaScript Style Guide
- Use meaningful variable names
- Add JSDoc comments for functions

### CSS Standards
- Use BEM methodology
- Follow mobile-first approach
- Use CSS custom properties for theming
- Maintain consistent spacing and typography

## Architecture Patterns

### MVC Pattern
The application follows the Model-View-Controller pattern:

- **Models**: Handle data logic and business rules
- **Views**: Handle presentation logic
- **Controllers**: Handle user input and coordinate between models and views

### Service Layer Pattern
For complex business logic, use service classes:

```php
<?php

namespace common\services;

use common\models\AcademyRequest;
use common\models\User;

/**
 * Trial Management Service
 */
class TrialService
{
    /**
     * Start trial for user
     * @param User $user
     * @param int $days
     * @return bool
     */
    public function startTrial(User $user, int $days = 7): bool
    {
        $user->trial_started_at = time();
        $user->trial_expires_at = time() + ($days * 24 * 60 * 60);
        
        return $user->save();
    }

    /**
     * Check if user trial is expired
     * @param User $user
     * @return bool
     */
    public function isTrialExpired(User $user): bool
    {
        return $user->trial_expires_at && $user->trial_expires_at <= time();
    }
}
```

## Database Design

### Naming Conventions
- Table names: snake_case, plural
- Column names: snake_case
- Foreign keys: `{table}_id`
- Indexes: `idx_{table}_{column}`

### Migration Guidelines
```php
<?php

use yii\db\Migration;

class m240915_000000_add_trial_fields_to_user extends Migration
{
    public function safeUp()
    {
        // Add columns
        $this->addColumn('user', 'trial_started_at', $this->integer()->null());
        $this->addColumn('user', 'trial_expires_at', $this->integer()->null());
        $this->addColumn('user', 'academy_id', $this->integer()->null());
        
        // Add indexes
        $this->createIndex('idx_user_trial_started_at', 'user', 'trial_started_at');
        $this->createIndex('idx_user_trial_expires_at', 'user', 'trial_expires_at');
        $this->createIndex('idx_user_academy_id', 'user', 'academy_id');
        
        // Add foreign key
        $this->addForeignKey(
            'fk_user_academy_id',
            'user',
            'academy_id',
            'academies',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // Drop foreign key
        $this->dropForeignKey('fk_user_academy_id', 'user');
        
        // Drop indexes
        $this->dropIndex('idx_user_academy_id', 'user');
        $this->dropIndex('idx_user_trial_expires_at', 'user');
        $this->dropIndex('idx_user_trial_started_at', 'user');
        
        // Drop columns
        $this->dropColumn('user', 'academy_id');
        $this->dropColumn('user', 'trial_expires_at');
        $this->dropColumn('user', 'trial_started_at');
    }
}
```

## API Development

### RESTful API Guidelines
- Use HTTP methods appropriately (GET, POST, PUT, DELETE)
- Return consistent JSON responses
- Use proper HTTP status codes
- Implement pagination for list endpoints

### API Response Format
```php
// Success Response
return [
    'success' => true,
    'data' => $data,
    'message' => 'Operation completed successfully'
];

// Error Response
return [
    'success' => false,
    'error' => [
        'code' => 'VALIDATION_ERROR',
        'message' => 'Invalid input data',
        'details' => $validationErrors
    ]
];
```

### API Controller Example
```php
<?php

namespace api\controllers;

use common\models\AcademyRequest;
use yii\rest\ActiveController;
use yii\web\Response;

class AcademyRequestController extends ActiveController
{
    public $modelClass = AcademyRequest::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Approve academy request
     * @param int $id
     * @return array
     */
    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        
        if ($model->approve()) {
            return [
                'success' => true,
                'message' => 'Academy request approved successfully',
                'data' => $model
            ];
        }
        
        return [
            'success' => false,
            'error' => [
                'code' => 'APPROVAL_FAILED',
                'message' => 'Failed to approve academy request'
            ]
        ];
    }
}
```

## Testing Guidelines

### Unit Testing
```php
<?php

namespace common\tests\unit\models;

use common\models\AcademyRequest;
use Codeception\Test\Unit;

class AcademyRequestTest extends Unit
{
    public function testApprove()
    {
        $model = new AcademyRequest([
            'academy_name' => 'Test Academy',
            'manager_name' => 'Test Manager',
            'email' => 'test@example.com',
            'phone' => '+966501234567',
            'status' => AcademyRequest::STATUS_PENDING,
            'requested_at' => time()
        ]);
        
        $this->assertTrue($model->save());
        $this->assertTrue($model->approve());
        $this->assertEquals(AcademyRequest::STATUS_APPROVED, $model->status);
    }
}
```

### Integration Testing
```php
<?php

namespace api\tests\functional;

use api\tests\FunctionalTester;

class AcademyRequestCest
{
    public function testCreateAcademyRequest(FunctionalTester $I)
    {
        $I->sendPOST('/academy-requests', [
            'academy_name' => 'Test Academy',
            'manager_name' => 'Test Manager',
            'email' => 'test@example.com',
            'phone' => '+966501234567'
        ]);
        
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson(['success' => true]);
    }
}
```

## Development Workflow

### 1. Feature Development
```bash
# Create feature branch
git checkout -b feature/trial-management

# Make changes
# Add tests
# Update documentation

# Commit changes
git add .
git commit -m "Add trial management functionality"

# Push branch
git push origin feature/trial-management

# Create pull request
```

### 2. Code Review Process
- All code must be reviewed before merging
- At least one approval required
- Automated tests must pass
- Code coverage should be maintained

### 3. Release Process
```bash
# Update version
composer version patch

# Create release tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0

# Deploy to production
./deploy.sh production
```

## Performance Guidelines

### Database Optimization
- Use indexes appropriately
- Avoid N+1 queries
- Use eager loading for relationships
- Implement query caching

### Caching Strategy
```php
// Cache frequently accessed data
$cacheKey = "academy_request_{$id}";
$data = Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
    return AcademyRequest::findOne($id);
}, 3600); // Cache for 1 hour
```

### Memory Management
- Use unset() for large variables
- Implement pagination for large datasets
- Monitor memory usage in long-running processes

## Security Guidelines

### Input Validation
```php
public function rules()
{
    return [
        [['email'], 'email'],
        [['phone'], 'match', 'pattern' => '/^\+966\d{9}$/'],
        [['academy_name'], 'string', 'max' => 255],
        [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_APPROVED]],
    ];
}
```

### SQL Injection Prevention
- Always use parameterized queries
- Use ActiveRecord or Query Builder
- Validate and sanitize user input

### XSS Prevention
```php
// In views
<?= Html::encode($user->name) ?>

// In controllers
$name = Html::encode($request->post('name'));
```

## Debugging and Logging

### Debug Configuration
```php
// config/debug.php
return [
    'class' => 'yii\debug\Module',
    'allowedIPs' => ['127.0.0.1', '::1'],
    'panels' => [
        'db' => 'yii\debug\panels\DbPanel',
        'log' => 'yii\debug\panels\LogPanel',
    ],
];
```

### Logging Best Practices
```php
// Log important events
Yii::info('User started trial', 'trial');
Yii::warning('Trial expired for user: ' . $user->id, 'trial');
Yii::error('Failed to approve academy request: ' . $e->getMessage(), 'academy');
```

## Documentation Standards

### Code Documentation
- Document all public methods
- Include parameter types and return types
- Provide usage examples
- Update documentation when code changes

### README Files
- Include setup instructions
- Document configuration options
- Provide usage examples
- Keep documentation up to date

## Tools and IDE Setup

### Recommended IDE
- PhpStorm with Yii2 plugin
- VS Code with PHP extensions
- Sublime Text with PHP packages

### Development Tools
```bash
# Install development dependencies
composer install --dev

# Run code style checks
./vendor/bin/phpcs --standard=PSR12 common/

# Run static analysis
./vendor/bin/phpstan analyse common/

# Run tests
./vendor/bin/codecept run
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintainer:** Vult Development Team
