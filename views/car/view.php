<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Car */
?>
<div class="car-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'number',
            'status',
            'driver_id',
            'mileage',
        ],
    ]) ?>

</div>
