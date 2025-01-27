<?php

use yii\helpers\Html;

// \frontend\assets\FrontendAsset::register($this);


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message bing composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office"
>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title><?php echo Html::encode($this->title) ?></title>

    <style type="text/css">
        @import url("https://fonts.googleapis.com/css?family=Cairo");

        body {
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            font-family: "Cairo", sans-serif;
        }

        table {
            border-spacing: 0;
        }

        table td {
            border-collapse: collapse;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        .ReadMsgBody {
            width: 100%;
            background-color: #ebebeb;
        }

        table {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        .yshortcuts a {
            border-bottom: none !important;
        }

        @media screen and (max-width: 599px) {
            .force-row,
            .container {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
        @media screen and (max-width: 400px) {
            .container-padding {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }
        .ios-footer a {
            color: #aaaaaa !important;
            text-decoration: underline;
        }
        a[href^="x-apple-data-detectors:"],
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>


    <?php $this->head() ?>
</head>

<body
    style="margin:0; padding:0; direction: ltr"
    bgcolor="#F0F0F0"
    leftmargin="0"
    topmargin="0"
    marginwidth="0"
    marginheight="0"
>

<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    <!--     Entice the open with some amazing preheader text. Use a little mystery and get those subscribers to read through...
     --></div>



<?php $this->beginBody() ?>
<table
        border="0"
        width="100%"
        height="100%"
        cellpadding="0"
        cellspacing="0"
        bgcolor="#F0F0F0"
>
    <tr>
        <td
                align="center"
                valign="top"
                bgcolor="#F0F0F0"
                style="background-color: #F0F0F0;"
        >
            <br />
            <!-- 600px container (white background) -->
            <table
                    border="0"
                    width="600"
                    cellpadding="0"
                    cellspacing="0"
                    class="container"
                    style="width:600px;max-width:600px"
            >
                <tr>
                    <td
                            class="container-padding header"
                            align="left"
                            style="width: 600px;float: right;background: #1c6856;font-family: 'Cairo', sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;padding-top:12px;color:#DF4726;padding-left:24px;padding-right:24px;text-align: right"
                    >

                        <span
                                style="color:#fff;float: left;margin: 30px;font-size: 18px;"
                        >App</span
                        >
                    </td>
                </tr>
<?php echo $content ?>

                <tr>
                    <td
                            class="container-padding footer-text"
                            align="right"
                            style="font-family: 'Cairo', sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px;direction: rtl"
                    >
                        <br /><br />
                        حقوق النشر © App جميع الحقوق محفوظة
                        <br /><br />
                    </td>
                </tr>
            </table>
            <!--/600px container -->
        </td>
    </tr>
</table>

<?php $this->endBody() ?>
</body>


</html>
<?php $this->endPage() ?>
