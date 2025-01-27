<?php
/**
 * @var View $this
 * @var string $dropDownClass
 * @var string $wrapperClass
 * @var array $languages
 * @var boolean $forBootstrapNavbar
 * @var boolean $useFullLanguageName
 */

use webvimark\behaviors\multilanguage\MultiLanguageHelper;
use yii\helpers\Html;
use yii\web\View;

?>

<?php if ( $forBootstrapNavbar ): ?>
	<a href="javascript:void(0);" class="dropdown-toggle"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img width="22px" src="/img/flags/<?= Yii::$app->language=='en'?'gb.png':'sa.png'  ?>" alt="" />
        <span class="link-text"><?= $useFullLanguageName ? @$languages[Yii::$app->language] : Yii::$app->language ?></span></a>
		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
			<!-- <a class="dropdown-item" href="#">English</a>
			<a class="dropdown-item" href="#">Arabic</a> -->

			<?php foreach ($languages as $langCode => $langName): ?>
					<?php $langName = $useFullLanguageName ? $langName : $langCode ?>
					<?php if ( $langCode != Yii::$app->language ): ?>
						<?= Html::a($langName, MultiLanguageHelper::createMultilanguageReturnUrl($langCode) , ['class' => 'dropdown-item'] ) ?>
					<?php endif; ?>

				<?php endforeach ?>

		</div>
	
	
	

<?php else: ?>
	<div class='<?= $wrapperClass; ?>'>

		<?= Html::beginForm() ?>

		<?php foreach($languages as $lang => $langName): ?>
			<?= Html::hiddenInput($lang, MultiLanguageHelper::createMultilanguageReturnUrl($lang)) ?>
		<?php endforeach ?>

		<?= Html::dropDownList(
			'_language_selector',
			Yii::$app->language,
			$languages,
			[
				'class'=>$dropDownClass,
				'onchange'=>'submit({d:"aa"})',
			]
		) ?>

		<?= Html::endForm() ?>
	</div>

<?php endif; ?>

