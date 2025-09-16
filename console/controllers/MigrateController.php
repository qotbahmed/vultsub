<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Database migration controller
 */
class MigrateController extends Controller
{
    /**
     * Run database migrations
     */
    public function actionUp()
    {
        $this->stdout("Running database migrations...\n", Console::FG_GREEN);
        
        try {
            $migration = new \yii\console\controllers\MigrateController('migrate', Yii::$app);
            $migration->runAction('up', ['interactive' => false]);
            
            $this->stdout("Migrations completed successfully!\n", Console::FG_GREEN);
            return ExitCode::OK;
        } catch (\Exception $e) {
            $this->stderr("Migration failed: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
    
    /**
     * Create a new migration
     */
    public function actionCreate($name)
    {
        $this->stdout("Creating migration: $name\n", Console::FG_GREEN);
        
        try {
            $migration = new \yii\console\controllers\MigrateController('migrate', Yii::$app);
            $migration->runAction('create', ['name' => $name, 'interactive' => false]);
            
            $this->stdout("Migration created successfully!\n", Console::FG_GREEN);
            return ExitCode::OK;
        } catch (\Exception $e) {
            $this->stderr("Failed to create migration: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
