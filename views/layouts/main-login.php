<?php
    use yii\helpers\Html;
?>
<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title><?= Html::encode($this->title) ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="/theme/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet"/>
    <link href="/theme/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/theme/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="/theme/assets/css/animate.min.css" rel="stylesheet"/>
    <link href="/theme/assets/css/style.min.css" rel="stylesheet"/>
    <link href="/theme/assets/css/style-responsive.min.css" rel="stylesheet"/>
    <link href="/css/site.css" rel="stylesheet">
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/theme/assets/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top">
<style type="text/css">
    body {
        background: url(/img/login_bg.jpg) no-repeat;
        background-position: center;
    }
    .login {
        margin: -200px 0 0 !important;
    }
    .login .login-content{
        background-color: white;
        box-shadow: 0 0 10px 5px rgba(221, 221, 221, 1);
        width: 385px !important;
        min-height: 405px;
        border-radius: 10px;
        padding:50px;
    }

    .login-logo{
        text-align:center;
        margin-bottom:30px;
    }

    .login-logo img{
        width:80px;
    }
    .field-loginform-username{
        margin-bottom:10px;
    }
    </style>
<?php $this->beginBody() ?>
<!-- begin #page-loader -->
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<!-- end #page-loader -->
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger fade in m-b-15">
        <strong>Ошибка!</strong>
        <?= Yii::$app->session->getFlash('error') ?>
        <span class="close" data-dismiss="alert">×</span>
        <div>

        </div>
    </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success fade in m-b-15">
        <strong>Поздравляем!</strong>
        <?= Yii::$app->session->getFlash('success') ?>
        <span class="close" data-dismiss="alert">×</span>
        <div>

        </div>
    </div>
<?php endif; ?>
<!-- begin #page-container -->
<div id="page-container" class="fade">
    <!-- begin login -->
    <?= $content ?>
    <!-- end login -->

</div>
<!-- end page container -->

<!-- ================== BEGIN BASE JS ================== -->
<script src="/theme/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="/theme/assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="/theme/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="/theme/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="/theme/assets/plugins/crossbrowserjs/html5shiv.js"></script>
<script src="/theme/assets/plugins/crossbrowserjs/respond.min.js"></script>
<script src="/theme/assets/plugins/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<!--<script src="/theme/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>-->
<script src="/theme/assets/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="/theme/assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
    $(document).ready(function () {
        App.init();
    });
</script>
<?php $this->endBody(); ?>
</body>
</html>
