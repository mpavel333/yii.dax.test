<?php

/**
 * @var $this \yii\web\View
 * @var $settings \app\models\Settings[]
 * @var $company \app\models\Companies[]
 */

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = "Настройки";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-shadow m-b-10">

        <?php $form = ActiveForm::begin(['method' => "POST"]); ?>

        <?= $form->field($model, 'flight_index')->textInput()->label('Идекс заявок (номер следующей заявки)') ?>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
</div>