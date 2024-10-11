<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Salary */
?>
<div class="salary-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'percent',
            'percent_with_nds',
            'withdraw',
            'user_id',
        ],
    ]) ?>

</div>
