<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

?>
<script async="" src="https://www.google-analytics.com/analytics.js"></script>
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<div class="content" id="content">

        <?php Pjax::begin(['enablePushState' => false, 'id' => 'report-messages-pjax']) ?>
        <?php if(Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success fade in m-b-15">
                <strong>Поздравляем!</strong>
                <?=Yii::$app->session->getFlash('success')?>
                <span class="close" data-dismiss="alert">×</span>
                <div>

                </div>
            </div>
        <?php endif; ?>

        <?php if(Yii::$app->session->hasFlash('info')): ?>
            <div class="alert alert-info fade in m-b-15">
                <strong>Уведомление!</strong>
                <?=Yii::$app->session->getFlash('info')?>
                <span class="close" data-dismiss="alert">×</span>
                <div>

                </div>
            </div>
        <?php endif; ?>

        <?php if(Yii::$app->session->hasFlash('warning')): ?>
            <div class="alert alert-warning fade in m-b-15">
                <strong>Внимание!</strong>
                <?=Yii::$app->session->getFlash('warning')?>
                <span class="close" data-dismiss="alert">×</span>
                <div>

                </div>
            </div>
        <?php endif; ?>

        <?php if(Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger fade in m-b-15">
                <strong>Ошибка!</strong>
                <?=Yii::$app->session->getFlash('error')?>
                <span class="close" data-dismiss="alert">×</span>
                <div>

                </div>
            </div>
        <?php endif; ?>
        <?php Pjax::end() ?>

        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1 class="page-header">Basic Tables</h1>
        <?php } else { ?>
        <?php } ?>

<!--         <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?> -->

        <?= $content ?>
    </div>

