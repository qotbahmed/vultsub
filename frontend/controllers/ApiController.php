<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use frontend\models\AcademyRequest;
use frontend\models\Player;

/**
 * API controller for frontend API endpoints
 */
class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'cors' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * API index
     */
    public function actionIndex()
    {
        return ['message' => 'Connected successfully'];
    }

    /**
     * Academy requests API
     */
    public function actionAcademyRequests()
    {
        $method = Yii::$app->request->method;
        $id = Yii::$app->request->get('id');
        switch ($method) {
            case 'GET':
                if ($id) {
                    return $this->getAcademyRequest($id);
                } else {
                    return $this->getAcademyRequests();
                }
                break;
                
            case 'POST':
                return $this->createAcademyRequest();
                break;
                
            case 'PUT':
                return $this->updateAcademyRequest($id);
                break;
                
            case 'DELETE':
                return $this->deleteAcademyRequest($id);
                break;
                
            default:
                Yii::$app->response->statusCode = 405;
                return ['error' => 'Method not allowed'];
        }
    }

    /**
     * Players API
     */
    public function actionPlayers()
    {
        $method = Yii::$app->request->method;
        $id = Yii::$app->request->get('id');

        switch ($method) {
            case 'GET':
                if ($id) {
                    return $this->getPlayer($id);
                } else {
                    return $this->getPlayers();
                }
                break;
                
            case 'POST':
                return $this->createPlayer();
                break;
                
            case 'PUT':
                return $this->updatePlayer($id);
                break;
                
            case 'DELETE':
                return $this->deletePlayer($id);
                break;
                
            default:
                Yii::$app->response->statusCode = 405;
                return ['error' => 'Method not allowed'];
        }
    }

    /**
     * Portal integration API
     */
    public function actionPortalIntegration()
    {
        $method = Yii::$app->request->method;
        $endpoint = Yii::$app->request->get('endpoint');

        switch ($method) {
            case 'POST':
                switch ($endpoint) {
                    case 'create-academy':
                        return $this->createAcademyInPortal();
                    case 'sync-user':
                        return $this->syncUserToPortal();
                    case 'update-subscription':
                        return $this->updateSubscription();
                    default:
                        Yii::$app->response->statusCode = 404;
                        return ['error' => 'Endpoint not found'];
                }
                break;
                
            case 'GET':
                switch ($endpoint) {
                    case 'academy-status':
                        return $this->getAcademyStatus();
                    case 'business-analytics':
                        return $this->getBusinessAnalytics();
                    default:
                        Yii::$app->response->statusCode = 404;
                        return ['error' => 'Endpoint not found'];
                }
                break;
                
            default:
                Yii::$app->response->statusCode = 405;
                return ['error' => 'Method not allowed'];
        }
    }

    /**
     * Trial management API
     */
    public function actionTrialManagement()
    {
        $method = Yii::$app->request->method;
        $endpoint = Yii::$app->request->get('endpoint');

        switch ($method) {
            case 'GET':
                switch ($endpoint) {
                    case 'trial-status':
                        return $this->getTrialStatus();
                    case 'trial-features':
                        return $this->getTrialFeatures();
                    default:
                        Yii::$app->response->statusCode = 404;
                        return ['error' => 'Endpoint not found'];
                }
                break;
                
            case 'POST':
                switch ($endpoint) {
                    case 'extend-trial':
                        return $this->extendTrial();
                    case 'upgrade-account':
                        return $this->upgradeAccount();
                    default:
                        Yii::$app->response->statusCode = 404;
                        return ['error' => 'Endpoint not found'];
                }
                break;
                
            default:
                Yii::$app->response->statusCode = 405;
                return ['error' => 'Method not allowed'];
        }
    }

    // Helper methods for academy requests
    private function getAcademyRequests()
    {
        $requests = AcademyRequest::find()->all();
        return ['data' => $requests];
    }

    private function getAcademyRequest($id)
    {
        $request = AcademyRequest::findOne($id);
        if (!$request) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Academy request not found'];
        }
        return ['data' => $request];
    }

    private function createAcademyRequest()
    {
        $request = new AcademyRequest();
        if ($request->load(Yii::$app->request->post()) && $request->save()) {
            return ['data' => $request, 'message' => 'Academy request created successfully'];
        }
        
        Yii::$app->response->statusCode = 400;
        return ['error' => 'Failed to create academy request', 'errors' => $request->errors];
    }

    private function updateAcademyRequest($id)
    {
        $request = AcademyRequest::findOne($id);
        if (!$request) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Academy request not found'];
        }
        
        if ($request->load(Yii::$app->request->post()) && $request->save()) {
            return ['data' => $request, 'message' => 'Academy request updated successfully'];
        }
        
        Yii::$app->response->statusCode = 400;
        return ['error' => 'Failed to update academy request', 'errors' => $request->errors];
    }

    private function deleteAcademyRequest($id)
    {
        $request = AcademyRequest::findOne($id);
        if (!$request) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Academy request not found'];
        }
        
        if ($request->delete()) {
            return ['message' => 'Academy request deleted successfully'];
        }
        
        Yii::$app->response->statusCode = 400;
        return ['error' => 'Failed to delete academy request'];
    }

    // Helper methods for players
    private function getPlayers()
    {
        $players = Player::find()->all();
        return ['data' => $players];
    }

    private function getPlayer($id)
    {
        $player = Player::findOne($id);
        if (!$player) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Player not found'];
        }
        return ['data' => $player];
    }

    private function createPlayer()
    {
        $player = new Player();
        if ($player->load(Yii::$app->request->post()) && $player->save()) {
            return ['data' => $player, 'message' => 'Player created successfully'];
        }
        
        Yii::$app->response->statusCode = 400;
        return ['error' => 'Failed to create player', 'errors' => $player->errors];
    }

    private function updatePlayer($id)
    {
        $player = Player::findOne($id);
        if (!$player) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Player not found'];
        }
        
        if ($player->load(Yii::$app->request->post()) && $player->save()) {
            return ['data' => $player, 'message' => 'Player updated successfully'];
        }
        
        Yii::$app->response->statusCode = 400;
        return ['error' => 'Failed to update player', 'errors' => $player->errors];
    }

    private function deletePlayer($id)
    {
        $player = Player::findOne($id);
        if (!$player) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Player not found'];
        }
        
        if ($player->delete()) {
            return ['message' => 'Player deleted successfully'];
        }
        
        Yii::$app->response->statusCode = 400;
        return ['error' => 'Failed to delete player'];
    }

    // Portal integration methods
    private function createAcademyInPortal()
    {
        // Implementation for creating academy in portal
        return ['message' => 'Academy created in portal'];
    }

    private function syncUserToPortal()
    {
        // Implementation for syncing user to portal
        return ['message' => 'User synced to portal'];
    }

    private function updateSubscription()
    {
        // Implementation for updating subscription
        return ['message' => 'Subscription updated'];
    }

    private function getAcademyStatus()
    {
        // Implementation for getting academy status
        return ['data' => ['status' => 'active']];
    }

    private function getBusinessAnalytics()
    {
        // Implementation for getting business analytics
        return ['data' => ['revenue' => 0, 'academies' => 0]];
    }

    // Trial management methods
    private function getTrialStatus()
    {
        // Implementation for getting trial status
        return ['data' => ['trial_active' => true, 'days_left' => 7]];
    }

    private function getTrialFeatures()
    {
        // Implementation for getting trial features
        return ['data' => ['features' => ['basic_management', 'limited_players']]];
    }

    private function extendTrial()
    {
        // Implementation for extending trial
        return ['message' => 'Trial extended'];
    }

    private function upgradeAccount()
    {
        // Implementation for upgrading account
        return ['message' => 'Account upgraded'];
    }
}
