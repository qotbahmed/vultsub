<?php

namespace common\helpers;

use Yii;
use yii\base\DynamicModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\validators\FileValidator;
use yii\web\UploadedFile;

class MyUploadAction extends \trntv\filekit\actions\UploadAction
{
    /**
     * @return array
     * @throws \HttpException
     */
    public function run()
    {
        $result = [];
        $uploadedFiles = UploadedFile::getInstancesByName($this->fileparam);

        foreach ($uploadedFiles as $uploadedFile) {

            $FileValoidator = new FileValidator();
            $FileValoidator->extensions =  array("pdf","docx","doc","csv","xls","png","jpg","jpeg","gif","xlsx");
            // global check for image type
            if(! $FileValoidator->validate($uploadedFile ,$erros)){
                $output['error'] = true;
                $output['errors'] = $erros;
            }else {

                /* @var \yii\web\UploadedFile $uploadedFile */
                $output = [
                    $this->responseNameParam => Html::encode($uploadedFile->name),
                    $this->responseMimeTypeParam => $uploadedFile->type,
                    $this->responseSizeParam => $uploadedFile->size,
                    $this->responseBaseUrlParam => $this->getFileStorage()->baseUrl,
                ];
                if ($uploadedFile->error === UPLOAD_ERR_OK) {
                    $validationModel = DynamicModel::validateData(['file' => $uploadedFile], $this->validationRules);
                    if (!$validationModel->hasErrors()) {
                        $ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
                        $newName = rand(1,1000000000).date("m-d-Y-h-i-s", time()).'.' .$ext; ///$uploadedFile->name

                          $path = $this->getFileStorage()->save($uploadedFile, false, true, $this->saveConfig, $this->uploadPath, $newName);

                        //echo $path ; die;

                        if ($path) {
                            $output[$this->responsePathParam] = $path;
                            $output[$this->responseUrlParam] = $this->getFileStorage()->baseUrl . '/' . $path;
                            $output[$this->responseDeleteUrlParam] = Url::to([$this->deleteRoute, 'path' => $path]);
                            $paths = \Yii::$app->session->get($this->sessionKey, []);
                            $paths[] = $path;
                            \Yii::$app->session->set($this->sessionKey, $paths);
                            $this->afterSave($path);

                        } else {
                            $output['error'] = true;
                            $output['errors'] = [];
                        }

                    } else {
                        $output['error'] = true;
                        $output['errors'] = $validationModel->getFirstError('file');
                    }
                } else {
                    $output['error'] = true;
                    $output['errors'] = $this->resolveErrorMessage($uploadedFile->error);
                }
                
                $result['files'][] = $output;
            }
        }
        return $this->multiple ? $result : array_shift($result);
    }
}
