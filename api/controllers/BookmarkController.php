<?php

namespace api\controllers;

use api\helpers\ResponseHelper;
use common\models\UserBookmark;
use Yii;

class BookmarkController extends MyActiveController
{
    public $modelClass = UserBookmark::class;

    public function actionIndex()
    {
        $marks = UserBookmark::find()
            ->where(['user_id' => \Yii::$app->user->getId()])
            ->all();
        return ResponseHelper::sendSuccessResponse($marks);
    }

    public function actionCreateList()
    {
        $params = \Yii::$app->request->post();
        $bookmarks = isset($params['bookmarks']) ? $params['bookmarks'] : [];
        $userId = \Yii::$app->user->getId();

        if (empty($bookmarks)) {
            return ResponseHelper::sendFailedResponse(['MESSAGE' => Yii::t('backend', 'No bookmarks provided.')], 400);
        }

        foreach ($bookmarks as $bookmark) {
            $model = new UserBookmark();
            $model->user_id = $userId;
            if (!$model->save()) {
                return ResponseHelper::sendFailedResponse(['MESSAGE' => $model->firstErrors], 400);
            }
        }

        return ResponseHelper::sendSuccessResponse(['MESSAGE' => Yii::t('backend', 'Bookmarks created successfully.')]);
    }

    public function actionCreate()
    {
        $params = \Yii::$app->request->post();
        $model = new UserBookmark();
        if ($model->load(['UserBookmark' => $params]) && $model->validate()) {

            $model->user_id = \Yii::$app->user->getId();

            if (!$model->save()) {
                var_dump($model->errors);
            }

            return ResponseHelper::sendSuccessResponse($model);
        } else {
            return ResponseHelper::sendFailedResponse(['MESSAGE' => $model->firstErrors], 400);
        }

    }

    public function actionUpdate($id)
    {
        $params = \Yii::$app->request->post();
        $model = UserBookmark::findOne($id);
        if ($model->load(['UserBookmark' => $params]) && $model->validate()) {

            $model->user_id = \Yii::$app->user->getId();

            if (!$model->save()) {
                var_dump($model->errors);
            }

            return ResponseHelper::sendSuccessResponse($model);
        } else {
            return ResponseHelper::sendFailedResponse(['MESSAGE' => $model->firstErrors], 400);
        }

    }

    public function actionView($id)
    {
        $mark = UserBookmark::findOne($id);

        if ($mark) {
            return ResponseHelper::sendSuccessResponse($mark);
        }

        return ResponseHelper::sendFailedResponse(['MESSAGE' => Yii::t('backend', 'This record was not found.')], 400);
    }

    public function actionDelte($id)
    {
        $mark = UserBookmark::findOne($id);

        if ($mark) {
            if (!$mark->delete()) {
                return ResponseHelper::sendFailedResponse(['MESSAGE' => $mark->firstErrors], 400);
            }
            return ResponseHelper::sendSuccessResponse(['MESSAGE' => Yii::t('backend', 'Record deleted successfully.')]);
        }

        return ResponseHelper::sendFailedResponse(['MESSAGE' => Yii::t('backend', 'This record was not found.')], 400);
    }
}
