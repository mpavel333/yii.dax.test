<?php

use \yii\helpers\Html;

?>

 <?= Html::a("Договор", ['download-pdf', 'id' => $id, 'type' => 'contract'], ['class' => 'btn btn-primary btn-block', 'target' => '_blank']); ?>
 <?= Html::a("УПД", ['download-pdf', 'id' => $id, 'type' => 'upd'], ['class' => 'btn btn-primary btn-block', 'target' => '_blank']); ?>