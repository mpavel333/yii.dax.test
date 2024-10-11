<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Call */
?>
<div class="call-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'phone',
            'phone1',
            'phone2',
            'client_id',
            'status',
            'inn',
            'site',
            'industry',
            'city',
            'contact_name',
            'timezone',
            'result',
            'files',
            'user_id',
            'create_at',
        ],
    ]) ?>

</div>
