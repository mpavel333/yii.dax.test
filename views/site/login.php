<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
///* @var $model \common\models\LoginForm */

$this->title = 'Авторизация';

$fieldOptions1 = [
    'options' => ['class' => 'form-group m-b-20'],
    'inputTemplate' => "{input}"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group m-b-20'],
    'inputTemplate' => "{input}"
];

$session = \Yii::$app->session;
$step = isset($session['login_step']) ? $session['login_step'] : 1;

?>

<style type="text/css">
    .form-control.inverse-mode {
        background: rgba(255,255,255,1) !important;
        color: #000 !important;
    }
</style>
<div class="login animated fadeInDown" style="background: transparent !important;">
    <!-- begin brand -->
    <div class="login-header">
        <div class="brand">
            
        </div>
        <div class="icon">
            <i class="fa fa-sign-in"></i>
        </div>
    </div>
    <!-- end brand -->
    <div class="login-content">

    <div class="login-logo">
        <img src="/img/login_logo.png"/>
    </div>

    <div style="text-align: center; margin-top: 30px; color: #000 !important; margin-bottom: 20px;">
        <?php if($step == 1): ?>
            <div style="text-align: left; font-size: 17px;">
                <?= Html::a('Забыли пароль?', ['site/forget-password'], ['style' => 'color: #000;']) ?>
                <?= Html::a('Регистрация', ['site/register'], ['class' => 'pull-right', 'style' => 'color: #000;']) ?>
            </div>
        <?php elseif($step == 2): ?>
            <?= Html::a('Назад', ['site/step-backward'], ['style' => 'color: #000;']) ?>
        <?php endif; ?>     
         </div>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false], $options = ['class' => 'margin-bottom-0']); ?> 
        <?php if($step == 1): ?>
            <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label('Email')
                ->textInput(['placeholder' => $model->getAttributeLabel('email'), 'class' => 'form-control input-lg inverse-mode']) ?>
            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label('Введите пароль')
                ->passwordInput(['placeholder' => 'Пароль', 'class' => 'form-control input-lg inverse-mode']) ?>
        <?php elseif($step == 2): ?>
            <?= $form
                ->field($model, 'code', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('code'), 'class' => 'form-control input-lg inverse-mode']) ?>
        <?php endif; ?> 
             <div class="login-buttons">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block btn-lg', 'name' => 'login-button']) ?>        </div>

        <?php ActiveForm::end(); ?>    </div>
</div>


