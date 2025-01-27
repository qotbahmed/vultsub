<?php

namespace common\helpers\multiLang;


use webvimark\behaviors\multilanguage\language_selector_widget\LanguageSelector;
use yii\base\Widget;
use Yii;

class myLanguageSelector extends LanguageSelector
{
	const DROPDOWN = 'dropDown';
	const INLINE = 'inline';

	/**
	 * @return string|void
	 */
	public function run()
	{
		$languages = Yii::$app->params['mlConfig']['languages'];

		if (count($languages) > 1)
		{
			return $this->render($this->viewFile, [
				'languages'           => $languages,
				'dropDownClass'       => $this->dropDownClass,
				'wrapperClass'        => $this->wrapperClass,
				'forBootstrapNavbar'  => $this->forBootstrapNavbar,
				'useFullLanguageName' => $this->useFullLanguageName,
			]);
		}

		return null;
	}
}