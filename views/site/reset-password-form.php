<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ResetPasswordForm */
/* @var $form ActiveForm */

?>
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'oldPassword') ?><?= $form->field($model, 'newPassword') ?><?= $form->field($model, 'repeatPassword') ?>
<?php if(!Yii::$app->request->isAjax): ?>    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>    </div>
<?php endif; ?>
<?php ActiveForm::end(); ?>