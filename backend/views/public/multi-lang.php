
<ul class="nav nav-tabs translateTabs">
    <li style="    margin-top: 4px;font-weight: bold;"><?=Yii::t('backend','Switch Language')?>:</li>

    <?php
    $LangArray = Yii::$app->params['mlConfig']['languages'] ;
    if (isset($_SESSION['MenuV'] ) && $_SESSION['MenuV']=='dry' ){
        $LangArray = Yii::$app->params['mlConfigDry']['languages'] ;

    }
    ?>

    <?php
        // echo Yii::$app->language.' mmmm';
        // var_dump($LangArray);
    ?>

    <?php foreach ($LangArray as $languageCode => $languageName): ?>
        <!-- <li class="<?//= (Yii::$app->language == $languageCode) ? 'active' : '' ?>" id="Lswitch_<?= $languageCode ?>"> -->

        <li class="<?= (str_contains(Yii::$app->language, $languageCode) ) ? 'active' : '' ?>" id="Lswitch_<?= $languageCode ?>">        
            <a>
                <?= $languageName ?>
            </a>
        </li>

    <?php endforeach ?>
    
</ul>