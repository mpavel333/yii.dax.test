<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $content string */

//$isGuest = Yii::$app->user->isGuest;
//if($isGuest)
//    $theme = 'default';
//else
//    $theme = Yii::$app->user->identity->theme;

$additionalClass = "";

// if(in_array(Yii::$app->controller->id, ['flight', 'order'])){
    // $additionalClass = "page-sidebar-minified";
// }


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    app\assets\ColorAdminAsset::register($this);

    // dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <!-- ================== BEGIN BASE CSS STYLE ================== -->
        <?=$this->head()?>
        <link href="/theme/assets/css/theme/default.css" rel="stylesheet">
        <!-- ================== END BASE CSS STYLE ================== -->
        <script src="/theme/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
        <!--        <script src="/assets/pace/pace.min.js"></script>-->
        <?php
            $yandexToken = null;

            $yandexTokenModel = \app\models\YandexToken::find()->one();

            if($yandexTokenModel){
                $yandexToken = $yandexTokenModel->token;
            }

        ?>
        <?php if($yandexToken): ?>
            <script src="https://api-maps.yandex.ru/2.1/?apikey=<?= $yandexToken ?>&lang=ru_RU" type="text/javascript"></script>
        <?php endif; ?>
    </head>
    <body>

    <?php $this->beginBody() ?>

    <div id="page-container" class="page-container <?= $additionalClass ?> page-sidebar-fixed fade page-header-fixed in">
        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'header-menu.php',
            ['directoryAsset' => $directoryAsset]
        )?>


        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

        <?php /*= $this->render(
            'settings.php'
        ) */ ?>

    </div>



    <?php $this->endBody() ?>

    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>



    <?= $this->render(
        'main-script.php'
    ) ?>
    <style>
        [title='Выберите формат файла для экспорта']{
            padding: 3px !important;
            padding-bottom: 2px !important;
        }
    </style>
    <script>


        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-53034621-1', 'auto');
        ga('send', 'pageview');

    </script>
    <script>
<?php if(in_array(Yii::$app->controller->id, ['flight', 'order'])): ?>

// $('#sidebar').hover(function(){
    // $("#page-container").removeClass("page-sidebar-minified");
// }, function(){
//     $("#page-container").addClass("page-sidebar-minified");
// });

<?php endif; ?>

    </script>

    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>

