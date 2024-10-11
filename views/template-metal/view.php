<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
?>
<div class="template-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'type',
            'text:ntext',
        ],
    ]) ?>

</div>
