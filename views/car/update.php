<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Car */
?>
<div class="car-update">

    <?= $this->render(\Yii::$app->controller->id == 'car' ? '_form' : '_form_rent', [
        'model' => $model,
    ]) ?>

</div>
