<?php
namespace common\helpers\multiLang;

use webvimark\behaviors\multilanguage\input_widget\MultiLanguageActiveField;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use Yii;
class MyMultiLanguageActiveField extends MultiLanguageActiveField
{
	/**
	 * @var ActiveRecord
	 */
	public $model;

	/**
	 * @var string
	 */
	public $attribute;

	/**
	 * @var array
	 */
	public $inputOptions = ['class'=>'form-control'];

	/**
	 * "textInput" or "textArea"
	 *
	 * @var string
	 */
	public $inputType = 'textInput';

	/**
	 * @return string
	 */
	public function run()
	{
		if ( isset(Yii::$app->params['mlConfig']['languages']) && count(Yii::$app->params['mlConfig']['languages']) > 1 )
			return $this->render('index');
		else
			return $this->getInputField($this->attribute);
	}

	/**
	 * @param string $attribute
	 *
	 * @return string
	 */
	public function getInputField($attribute)
	{
		if ( $this->inputType == 'textArea' )
			return Html::activeTextarea($this->model, $attribute, $this->inputOptions);
		else
			return Html::activeTextInput($this->model, $attribute, $this->inputOptions);
	}
} 