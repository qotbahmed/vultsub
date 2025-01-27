<?php

use kartik\grid\GridView;

$config = [
    'timeZone' => 'Asia/Riyadh',
    'components' => [
        'assetManager' => [
            'class' => yii\web\AssetManager::class,
            'linkAssets' => env('LINK_ASSETS'),
            'appendTimestamp' => YII_ENV_DEV,
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --no-color'],
                ],
            ],
            'bundles' => [
                'yii\bootstrap4\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],


        ],
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV2' => '6LedP4UhAAAAAFLPhY3IJbRY5njaP7pMd058cUqW',
            'secretV2' => '6LedP4UhAAAAAG9feK6FZkgvyi31vSFHnR3pUMC-',
            'siteKeyV3' => '6LfEB4UhAAAAAPpiiPpYe-q7vRzpk5UkjwJGiLZB',
            'secretV3' => '6LfEB4UhAAAAALL6QLkmBNSWYNeUwIj-p8j1PSAj',
        ],

        'view' => [
            'class' => 'yii\web\View',
            'on afterRender' => function ($event) {
                \newerton\fancybox3\FancyBox::widget([
                    'target' => '.fancybox',
                    'config' => [
                        'loop' => false,
                        'maxWidth' => '80%',
                        'maxHeight' => '80%',
                        'toolbar'           => true,
                        'arrows' => true,
                        'buttons' => [
                            'fullScreen',
                            'thumbs',
                            'close'
                        ],
                        'spinnerTpl'        => '<div class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>',
                        'baseTpl' => '
                        <div class="fancybox-container fancybox-bootstrap-modal fancybox-custom-modal" role="dialog">
                            <div class=" modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"></h5>
                                    <a type="button" class="isax h5 pointer isax-close-circle" data-fancybox-close aria-label="Close"></a>
                                </div>
                                <div class="modal-body fancybox-stage"></div>
                            </div>
                        </div>
                        </div>
        ',
                        'smallBtn' => false,
                        'animationEffect' => 'fade',
                    ],
                ]);
            },
        ],


        'formatter' => [
            'defaultTimeZone' => 'Asia/Riyadh', // Set default timezone

        ],

    ],
    'as locale' => [
        'class' => common\behaviors\LocaleBehavior::class,
        'enablePreferredLanguage' => true
    ],
    'container' => [
        'definitions' => [
            \yii\widgets\LinkPager::class => \yii\bootstrap4\LinkPager::class,
        ],
    ],
];



if (YII_DEBUG == "true") {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'allowedIPs' => ['*'],
    ];
}
if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'allowedIPs' => ['*'],
    ];
}


return $config;
