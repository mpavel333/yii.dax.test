<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
///* @var $model \common\models\LoginForm */

$this->title = 'Регистрация';

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

    </div>
    <!-- end brand -->
    <div class="login-content">
        <div class="login-logo">
            <img src="/img/login_logo.png"/>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'reg-form', 'enableClientValidation' => false], $options = ['class' => 'margin-bottom-0']); ?> 
        <?= $form
            ->field($model, 'name', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('name'), 'class' => 'form-control input-lg inverse-mode ']) ?>
        <?= $form
            ->field($model, 'organizationName', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('organizationName'), 'class' => 'form-control input-lg inverse-mode ']) ?>
        <?= $form
            ->field($model, 'inn', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('inn'), 'class' => 'form-control input-lg inverse-mode ']) ?>
        <?= $form
            ->field($model, 'phone', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('phone'), 'class' => 'form-control input-lg inverse-mode ']) ?>
        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email'), 'class' => 'form-control input-lg inverse-mode ']) ?>
        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control input-lg inverse-mode ']) ?>
             <div class="login-buttons">
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary btn-block btn-lg', 'name' => 'login-button']) ?>        </div>
        <div style="text-align: center; margin-top: 22px; color: #000;">
                <?= Html::a('Назад', ['site/login']) ?>
             </div>
        <?php ActiveForm::end(); ?>    </div>
</div>

