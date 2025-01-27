<?php
/**
 * @var View $this
 */
use yii\helpers\Html;
use yii\web\View;

?>





<!-- <ul class="nav nav-tabs translateTabs">
	<?php foreach (Yii::$app->params['mlConfig']['languages'] as $languageCode => $languageName): ?>

		<li class="<?= (Yii::$app->language == $languageCode) ? 'active' : '' ?>" id="<?= $languageCode ?>">
			<a>
				<?= $languageName ?>
			</a>
		</li>
	<?php endforeach ?>

</ul> -->

<div class="tab-content">

	<?php foreach (Yii::$app->params['mlConfig']['languages'] as $languageCode => $languageName): ?>

		<?php
		$attribute = $this->context->attribute;

		if ( $languageCode != Yii::$app->params['mlConfig']['default_language'] )
		{
			$attribute .= '_' . $languageCode;
		}

		$activeClass = (Yii::$app->language == $languageCode) ? 'active' : '';
		?>


		<div class="tab-pane <?= $activeClass ?> <?= $languageCode ?>">
			<i class="fa fa-language" style="position: absolute;right: 25px;top: 39px;"></i>
			<?= $this->context->getInputField($attribute) ?>
		</div>


	<?php endforeach ?>
</div>
