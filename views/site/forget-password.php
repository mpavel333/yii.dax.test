<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'Восстановление пароля';

$fieldOptions1 = [
    'options' => ['class' => 'form-group m-b-20'],
    'inputTemplate' => "{input}"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group m-b-20'],
    'inputTemplate' => "{input}"
];


?>
<div class="login animated fadeInDown">
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
        <p style="font-size: 14px; text-align: center;">Введите информацию для восстановления пароля</p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false], $options = ['class' => 'margin-bottom-0']); ?>        <?= $form
            ->field($model, 'email', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email'), 'class' => 'form-control input-lg inverse-mode']) ?>        <div class="login-buttons">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block btn-lg', 'name' => 'login-button']) ?>        </div>
        <?php ActiveForm::end(); ?>    </div>
</div>

