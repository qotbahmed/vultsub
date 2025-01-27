<li class="nav-item dropdown">
    <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle" id="bd-versions"
            data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
        <img src="/img/<?=Yii::$app->language?>.png">  <?= Yii::$app->params['availableLocales'][Yii::$app->language]  ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-versions">
        
        <?php
        foreach (Yii::$app->params['availableLocales'] as $item => $value) {
            if( Yii::$app->language != $item){
                echo '<li><a href="/site/set-locale?locale='.$item.'"><img src="/img/'.$item.'.png">'.$value.'</a></li>';
            }
        }
        ?>
    </ul>
</li>