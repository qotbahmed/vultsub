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

if(Yii::$app->language == 'ar'){

    echo '<a href="/site/index?_language=EN" class="nav-link btn btn-light link-dark rounded-pill fw-600 shadow-none d-inline-block mt-3 mt-lg-0 ">English</a>';
}else{
    echo '<a href="/site/index?_language=ar" class="nav-link btn btn-light link-dark rounded-pill fw-600 shadow-none d-inline-block mt-3 mt-lg-0">عربى</a>';

}

?>
